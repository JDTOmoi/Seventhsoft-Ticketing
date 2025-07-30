<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ],
        ];
    }

    protected function validationErrorMessages()
    {
        return [
            'password.required' => 'Tolong masukkan password baru.',
            'password.confirmed' => 'Password tersebut tidak sama.',
            'password.min' => 'Diperlukan 8 huruf atau lebih untuk password.',
        ];
    }

    protected function resetPassword($user, $password) // Overridden to get rid of old password reset token.
    {
        $this->setUserPassword($user, $password);

        $user->save();

        DB::table('password_resets')->where('email', $user->email)->delete();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
}