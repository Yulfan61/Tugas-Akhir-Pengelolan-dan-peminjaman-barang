<?php

// app/Http/Controllers/CalendarController.php
namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // Ambil semua borrowing yang statusnya Approved
        $borrowings = Borrowing::with('items')
            ->where('status', 'Approved')
            ->orderBy('borrow_date')
            ->get()
            ->groupBy(fn($b) => $b->borrow_date->format('Y-m-d'))
            ->map(function ($items) {
                return $items->map(function ($b) {
                    return [
                        'return_date' => optional($b->return_date)->format('Y-m-d'),
                        'items' => $b->items->pluck('name')->unique()->values()->toArray()
                    ];
                });
            });

        return view('calendar.index', compact('borrowings'));
    }
}
