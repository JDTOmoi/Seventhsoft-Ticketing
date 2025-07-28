<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'clients'; // Custom table name

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'username',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sendEmailVerificationNotification()
    {
        $otp = rand(100000, 999999);
        $this->otp = $otp;
        $this->otp_expires_at = now()->addMinutes(3);
        $this->save();

        Mail::to($this->email)->send(new OtpMail($otp, $this->name));
    }
}