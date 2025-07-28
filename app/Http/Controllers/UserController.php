<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function viewUpdateInfo() {
        $u = auth()->user();
        
        return view('user', compact('u'));
    }

    public function updateInfo(Request $request) {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:191|regex:/^[a-zA-Z\s]{1,191}$/',
            'profile_picture' => 'nullable|image|max:2048', // Optional image
        ], [
            'name.required'=> 'Nama pelanggan harus diisi.',
            'name.max' => 'Nama pelanggan harus dibawah 192 huruf.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
            $newFileName = $user->id . '_' . $safeName . '.' . $extension;
            if ($user->profile_picture && Storage::exists('users/' . $user->profile_picture)) {
                Storage::delete('users/' . $user->profile_picture);
            }
            $file->storeAs('users', $newFileName);
            $user->profile_picture = $newFileName;
        }

        $user->name = $request->input('name');
        $user->save();

        return redirect()->route('tickets');
    }
}