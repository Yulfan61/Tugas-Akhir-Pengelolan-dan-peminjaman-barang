<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DamageReport;
use App\Models\Item;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Tampilkan halaman daftar laporan kerusakan
    public function damageReports()
    {
        $reports = DamageReport::with(['item', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reports.damage_reports', compact('reports'));
    }

    // Tampilkan form create damage report
    public function createDamageReport()
    {
        $locations = Location::all(); // Tampilkan semua lokasi
        return view('reports.create_damage_report', compact('locations'));
    }

    // Simpan laporan kerusakan
    public function storeDamageReport(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'description' => 'required|string'
        ]);

        DamageReport::create([
            'item_id' => $validated['item_id'],
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'status' => 'Reported',
            'report_date' => now(),
        ]);

        return redirect()->route('reports.damage_reports')->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    // Update status laporan kerusakan
    public function updateDamageReport(Request $request, DamageReport $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:Reported,In Progress,Resolved'
        ]);

        $report->update(['status' => $validated['status']]);

        return redirect()->route('reports.damage_reports')->with('success', 'Status laporan diperbarui.');
    }

    // 🔥 Tambahkan method ini untuk AJAX get items by location
    public function getItemsByLocation($locationId)
    {
        $items = Item::where('location_id', $locationId)->get(['id', 'name']);
        return response()->json($items);
    }

    public function resolve($id)
    {
        $report = DamageReport::findOrFail($id);
        $report->status = 'Resolved'; // Ubah dari 'resolved' ke 'Resolved'
        $report->save();

        $item = $report->item;
        $unresolved = $item->reports()->where('status', '!=', 'Resolved')->exists();

        if (!$unresolved && in_array($item->condition, ['Rusak', 'Rusak Ringan'])) {
            $item->condition = 'Baik';
            $item->save();
        }

        return redirect()->back()->with('success', 'Report telah diselesaikan dan item diperbarui jika memungkinkan.');
    }

    public function selectLocation()
    {
        $locations = Location::all();
        return view('reports.select_location', compact('locations'));
    }

    public function exportByLocation(Location $location)
    {
        $items = Item::where('location_id', $location->id)->get();

        $pdf = Pdf::loadView('reports.pdf_by_location', compact('location', 'items'));
        return $pdf->download("Laporan-Barang-{$location->name}.pdf");
    }

    public function exportDamageReports()
    {
        $reports = DamageReport::with(['item', 'user'])
            ->whereNotNull('status')
            ->orderBy('created_at', 'desc')
            ->get();

        $tanggal = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('reports.pdf_damage_reports', compact('reports', 'tanggal'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Laporan-Kerusakan-{$tanggal}.pdf");
    }



}
