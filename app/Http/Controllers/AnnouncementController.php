<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function edit()
    {
        $announcement = Announcement::first();
        return view('dashboard.edit-announcement', compact('announcement'));
    }

    public function update(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        Announcement::updateOrCreate([], ['message' => $request->message]);
        return redirect()->route('dashboard')->with('success', 'Pesan sambutan berhasil diperbarui.');
    }
}
