<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function showForm()
    {
        $data = session('otp_register');

        if (!$data) {
            return redirect()->route('register')->withErrors(['otp' => 'Data pendaftaran tidak ditemukan.']);
        }

        return view('auth.verify-otp', ['phone_number' => $data['phone_number']]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $otpSession = session('otp_register');

        if (!$otpSession) {
            return redirect()->route('register')->withErrors(['otp_code' => 'Sesi tidak valid, silakan daftar ulang.']);
        }

        if ($request->otp_code != $otpSession['otp_code']) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        // Simpan user ke database
        $user = \App\Models\User::create([
            'name' => $otpSession['name'],
            'email' => $otpSession['email'],
            'password' => $otpSession['password'],
            'phone_number' => $otpSession['phone_number'],
        ]);

        $user->assignRole('Peminjam');

        Auth::login($user);

        session()->forget('otp_register');

        return redirect()->route('dashboard');
    }

}
