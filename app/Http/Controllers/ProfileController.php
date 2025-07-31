<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $newEmail = $request->email;

        if ($newEmail !== $user->email) {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Kirim OTP ke email baru
            Mail::to($newEmail)->send(new OTPMail($otp));

            // Simpan data email baru & OTP di session
            session([
                'pending_email_update' => [
                    'email' => $newEmail,
                    'otp' => $otp,
                ]
            ]);

            return redirect()->route('email.otp.form')
                ->with('status', 'Kode OTP telah dikirim ke email baru Anda.');
        }

        // Jika tidak mengganti email, update langsung
        $user->name = $request->name;
        $user->save();

        return back()->with('status', 'Profile updated.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


}
