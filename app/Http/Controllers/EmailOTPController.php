<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailOTPController extends Controller
{
    public function form()
    {
        return view('profile.verify-email-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $otpSession = session('pending_email_update');

        if (!$otpSession) {
            return redirect()->route('profile.edit')->withErrors(['otp_code' => 'Session tidak ditemukan.']);
        }

        if ($request->otp_code != $otpSession['otp']) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        // OTP valid, update email user
        $user = auth()->user();
        $user->email = $otpSession['email'];
        $user->save();

        // Bersihkan session
        session()->forget('pending_email_update');

        return redirect()->route('profile.edit')->with('status', 'Email berhasil diperbarui.');
    }
}