<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            "email.required" => 'Mohon isi email Anda.',
            "email.email" => "Mohon isi email yang sah."
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Create password reset token
        $token = Password::createToken($user);

        // Send the custom email
        Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email, $user->name));

        return back()->with('status', 'Link reset password telah dikirimkan ke email Anda.');
    }

    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }
}