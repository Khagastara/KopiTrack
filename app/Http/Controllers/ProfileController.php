<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //


public function show()
{
    $user = Auth::user();
    return view('profile.show', compact('user'));
}

public function edit()
{
    $user = Auth::user();
    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:15',
    ]);

    $user = Auth::user();
    $user->update($request->only('name', 'alamat', 'no_hp'));

    return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
}

}
