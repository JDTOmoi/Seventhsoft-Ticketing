<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:191|regex:/^[a-zA-Z\s]{1,191}$/',
            'email' => 'required|unique:clients|max:191',
            'username' => 'required|unique:clients|max:31|min:8|regex:/^[a-zA-Z0-9]{8,31}$/',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]+)$/|min:10',
            'profile_picture' => 'nullable|image|max:2048', // Optional
            'password' => 'required|min:8|confirmed',
        ],[
            "name.required" => 'Mohon isi nama asli Anda.',
            "email.required" => 'Mohon isi email Anda.',
            "username.required" => 'Mohon isi username Anda.',
            "phone_number.required" => 'Mohon isi nomor telepon Anda.',
            "password.required" => 'Mohon isi password Anda.',
            "email.unique" => 'Email tersebut sudah dipakai.',
            "email.max" => 'Email harus dibawah 192 huruf.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            "username.unique" => 'Username tersebut sudah dipakai.',
            "username.min" => 'Username harus memiliki 8 huruf atau lebih.',
            'username.regex' => 'Username hanya boleh berisi huruf besar, huruf kecil, dan angka, tanpa simbol atau spasi.',
            "username.max" => 'Username harus dibawah 32 huruf.',
            'name.max' => 'Nama pelanggan harus dibawah 192 huruf.',
            'phone_number' => 'Nomor telepon tersebut tidak valid.',
            'password.min' => 'Diperlukan 8 huruf atau lebih untuk password.',
            'password.confirmed' => 'Password tersebut tidak sama.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * Handles profile_picture upload and additional fields.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'profile_picture' => null,
            'password' => Hash::make($data['password']),
        ]);

        // If a file was uploaded, handle storing it
        if (request()->hasFile('profile_picture')) {
            $file = request()->file('profile_picture');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = $user->id . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;
            $file->storeAs('users', $newFileName, 'public');
            $user->profile_picture = $newFileName;
            $user->save();
        }

        return $user;
    }
}
