<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\BorrowingCreated;
use App\Events\BorrowingDeleted;
use App\Events\BorrowingUpdated;
use App\Notifications\NewBorrowingNotification;
use App\Notifications\BorrowingStatusUpdated;
use Spatie\Permission\Models\Role;
use App\Events\ItemStockUpdated;


class BorrowingController extends Controller
{
    public function index()
    {
        $query = Borrowing::with(['items.location', 'user'])
            ->whereNotIn('status', ['Returned', 'Rejected']) // ✅ benar, array bukan string
            ->orderBy('created_at', 'desc');

        if (!auth()->user()->hasAnyRole(['Admin', 'Staff'])) {
            $query->where('user_id', auth()->id());
        }

        $borrowings = $query->paginate(9);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create(Request $request)
    {
        $locations = Location::all();
        $selectedLocationId = $request->get('location_id');
        $items = collect();

        if ($selectedLocationId) {
            $items = Item::where('location_id', $selectedLocationId)
                ->where('condition', 'Baik')
                ->get();
        }

        return view('borrowings.create', compact('items', 'locations', 'selectedLocationId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'required|exists:items,id',
            'quantities' => 'required|array',
        ]);

        $borrowDate = $validated['borrow_date'];
        $returnDate = $validated['return_date'] ?? $borrowDate;

        DB::beginTransaction();
        try {
            $itemsToAttach = [];

            foreach ($validated['item_ids'] as $itemId) {
                $quantity = $validated['quantities'][$itemId] ?? 0;

                // Lock baris item untuk mencegah race condition
                $item = \App\Models\Item::where('id', $itemId)->lockForUpdate()->first();

                if (!$item) {
                    DB::rollBack();
                    return back()->withErrors(['item_ids' => "Item ID $itemId tidak ditemukan."])->withInput();
                }

                // Hitung jumlah barang yang sedang dipinjam pada waktu overlap
                $conflictQty = DB::table('borrowings')
                    ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
                    ->where('borrowing_items.item_id', $itemId)
                    ->whereIn('borrowings.status', ['Approved', 'Pending'])
                    ->where(function ($query) use ($borrowDate, $returnDate) {
                        $query->whereBetween('borrow_date', [$borrowDate, $returnDate])
                            ->orWhereBetween('return_date', [$borrowDate, $returnDate])
                            ->orWhere(function ($query) use ($borrowDate, $returnDate) {
                                $query->where('borrow_date', '<=', $borrowDate)
                                    ->where('return_date', '>=', $returnDate);
                            });
                    })
                    ->sum('borrowing_items.quantity');

                if ($item->stock - $conflictQty < $quantity) {
                    DB::rollBack();
                    return back()->withErrors([
                        'item_ids' => "Stok item {$item->name} tidak mencukupi. Tersedia: " . ($item->stock - $conflictQty)
                    ])->withInput();
                }

                $itemsToAttach[$itemId] = ['quantity' => $quantity];
            }

            // Simpan data peminjaman
            $borrowing = \App\Models\Borrowing::create([
                'user_id' => auth()->id(),
                'borrow_date' => $borrowDate,
                'return_date' => $returnDate,
                'status' => 'Pending',
            ]);

            $borrowing->items()->attach($itemsToAttach);

            DB::commit();

            // Kirim event
            event(new \App\Events\BorrowingCreated($borrowing));

            // Kirim notifikasi ke Admin/Staff
            $users = \App\Models\User::role(['Admin', 'Staff'])->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\NewBorrowingNotification($borrowing));
            }

            return redirect()->route('borrowings.index')->with('success', 'Permintaan peminjaman berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }




    public function show(Borrowing $borrowing)
    {
        if (auth()->user()->hasRole('Peminjam') && $borrowing->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->load(['user', 'items', 'approver', 'returnApprover']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Staff'])) {
            abort(403, 'Unauthorized');
        }

        $locations = Location::all();
        $items = Item::where('condition', 'Baik')->get();
        $borrowing->load('items');
        return view('borrowings.edit', compact('borrowing', 'items', 'locations'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Staff'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Returned',
            'penalty' => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($borrowing, $validated) {
            $previousStatus = $borrowing->status;

            // Jika sebelumnya Approved dan sekarang menjadi bukan Approved, kembalikan stok
            if ($previousStatus === 'Approved' && $validated['status'] !== 'Approved') {
                foreach ($borrowing->items as $item) {
                    $item->stock += $item->pivot->quantity;
                    $item->save();
                }
            }

            // Jika sekarang diubah menjadi Approved
            if ($validated['status'] === 'Approved' && $previousStatus !== 'Approved') {
                foreach ($borrowing->items as $item) {
                    $totalStock = $item->stock;

                    $borrowedQuantity = DB::table('borrowings')
                        ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
                        ->where('borrowing_items.item_id', $item->id)
                        ->where('borrowings.status', 'Approved')
                        ->where('borrowings.id', '!=', $borrowing->id)
                        ->sum('borrowing_items.quantity');

                    $availableStock = $totalStock - $borrowedQuantity;

                    if ($availableStock < $item->pivot->quantity) {
                        $borrowing->update(['status' => 'Rejected']);
                        return redirect()->route('borrowings.index')->with('error', "Peminjaman otomatis ditolak karena stok {$item->name} tidak mencukupi. Sisa tersedia: $availableStock");
                    }

                    $item->stock -= $item->pivot->quantity;
                    $item->save();
                    event(new ItemStockUpdated($item));
                }

                $borrowing->approved_by = auth()->id();
                $borrowing->approved_at = now();
            }

            $borrowing->update([
                'status' => $validated['status'],
                'penalty' => $validated['penalty'] ?? $borrowing->penalty,
                'returned_by' => $borrowing->returned_by,
                'returned_at' => $borrowing->returned_at,
            ]);

            event(new BorrowingUpdated($borrowing));

            if ($borrowing->user_id !== auth()->id()) {
                $borrowing->user->notify(new BorrowingStatusUpdated($borrowing));
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }



    public function destroy(Borrowing $borrowing)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Staff'])) {
            abort(403, 'Unauthorized');
        }

        DB::transaction(function () use ($borrowing) {
            if ($borrowing->status === 'Approved') {
                foreach ($borrowing->items as $item) {
                    $item->stock += $item->pivot->quantity;
                    $item->save();
                    event(new ItemStockUpdated($item));
                }
            }

            $borrowingId = $borrowing->id;
            $borrowing->items()->detach();
            $borrowing->delete();

            // Kirim event realtime
            broadcast(new BorrowingDeleted($borrowingId))->toOthers();
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function returnForm(Borrowing $borrowing)
    {
        if (auth()->user()->hasRole('Peminjam') && $borrowing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $borrowing->load('items');
        return view('borrowings.return', compact('borrowing'));
    }

    public function returnProcess(Request $request, Borrowing $borrowing)
    {
        if (auth()->user()->hasRole('Peminjam') && $borrowing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'item_conditions' => 'required|array',
            'item_conditions.*' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'return_photos' => 'nullable|array',
            'return_photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request, $borrowing) {
            foreach ($borrowing->items as $item) {
                $condition = $request->item_conditions[$item->id] ?? 'Baik';

                $item->stock += $item->pivot->quantity;
                $item->save();
                event(new ItemStockUpdated($item));

                $pivotData = [
                    'condition' => $condition,
                ];

                if ($request->hasFile("return_photos.{$item->id}")) {
                    $photoPath = $request->file("return_photos.{$item->id}")->store('return_photos', 'public');
                    $pivotData['return_photo'] = $photoPath;
                }

                $borrowing->items()->updateExistingPivot($item->id, $pivotData);
            }

            $borrowing->update([
                'status' => 'Waiting Approval',
                'returned_at' => now(),
                'returned_by' => auth()->id(),
            ]);

            event(new BorrowingUpdated($borrowing));
        });

        return redirect()->route('borrowings.index')->with('success', 'Barang berhasil dikembalikan.');
    }



    public function history()
    {
        $query = Borrowing::with(['items', 'user'])
            ->whereIn('status', ['Returned', 'Rejected'])
            ->orderBy('return_date', 'desc'); // GANTI INI

        if (auth()->user()->hasRole('Peminjam')) {
            $query->where('user_id', auth()->id());
        }

        $borrowings = $query->paginate(10);

        return view('borrowings.history', compact('borrowings'));
    }

    public function approveReturn(Borrowing $borrowing)
    {
        if (!auth()->user()->hasAnyRole(['Admin', 'Staff'])) {
            abort(403);
        }

        DB::transaction(function () use ($borrowing) {
            foreach ($borrowing->items as $item) {
                event(new ItemStockUpdated($item)); // Untuk update stok realtime jika perlu
            }

            $borrowing->update([
                'status' => 'Returned',
                'returned_by' => auth()->id(),
                'returned_at' => now(),
            ]);

            event(new BorrowingUpdated($borrowing));
        });

        return redirect()->route('borrowings.show', $borrowing)->with('success', 'Pengembalian telah disetujui.');
    }

    public function downloadPDF($id)
    {
        $borrowing = Borrowing::with(['user', 'items'])->findOrFail($id);
        $pdf = Pdf::loadView('borrowings.pdf', compact('borrowing'));
        return $pdf->download('Detail-Peminjaman-' . $borrowing->id . '.pdf');
    }

    public function downloadHistoryPDF()
    {
        $query = Borrowing::with(['items', 'user'])
            ->whereIn('status', ['Returned', 'Rejected'])
            ->orderBy('return_date', 'desc');

        if (auth()->user()->hasRole('Peminjam')) {
            $query->where('user_id', auth()->id());
        }

        $borrowings = $query->get();

        return Pdf::loadView('borrowings.pdf-history', compact('borrowings'))
            ->download('Riwayat-Peminjaman.pdf');
    }

    public function payPenalty(Request $request, Borrowing $borrowing)
    {
        if (auth()->id() !== $borrowing->user_id) {
            abort(403);
        }

        $request->validate([
            'penalty_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('penalty_proof')->store('penalty_proofs', 'public');

        $borrowing->update([
            'penalty_status' => 'Paid',
            'penalty_proof' => $path,
        ]);

        return back()->with('success', 'Bukti pembayaran penalti berhasil diunggah.');
    }

}
