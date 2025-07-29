<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class VerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewEmailVerificationPage(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/tickets');
        }

        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6'], ['otp.required' => 'OTP harus diisi.', 'otp.digits' => 'OTP harus mempunyai 6 angka.']);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/tickets');
        }


        if (
            $user->otp === $request->otp &&
            $user->otp_expires_at &&
            $user->otp_expires_at->isFuture()
        ) {
            $user->markEmailAsVerified();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return redirect('/tickets');
        }

        return back()->withErrors(['otp' => 'OTP tersebut telah hangus atau salah']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/tickets');
        }


        if (!$user->otp || !$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            $otp = rand(100000, 999999);
            $request->user()->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(3),
            ]);

            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } else {
            Mail::to($user->email)->send(new OtpMail($user->otp, $user->name));
        }

        return back()->with('resent', true);
    }
}
