<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Merchant;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user()->admin;
        return view('admin.profile.index', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::user()->admin;
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user()->admin;

        $request->validate([
            'admin_name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $admin->account_id,
            'phone_number' => 'nullable|string|max:15',
            'username' => 'required|string|max:50|unique:accounts,username,' . $admin->account_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $account = Account::findOrFail($admin->account_id);
        $account->update([
            'admin_name' => $request->admin_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->filled('password') ? bcrypt($request->password) : $account->password,
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function merchantIndex()
    {
        $merchant = Auth::user()->merchant;
        return view('merchant.profile.index', compact('merchant'));
    }

    public function merchantEdit()
    {
        $merchant = Auth::user()->merchant;
        return view('merchant.profile.edit', compact('merchant'));
    }

    public function merchantUpdate(Request $request)
    {
        $merchant = Auth::user()->merchant;

        $request->validate([
            'merchant_name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,' . $merchant->id_account,
            'phone_number' => 'nullable|string|max:15',
            'username' => 'required|string|max:50|unique:accounts,username,' . $merchant->id_account,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $account = Account::findOrFail($merchant->id_account);
        $account->update([
            'merchant_name' => $request->merchant_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->filled('password') ? bcrypt($request->password) : $account->password,
        ]);

        return redirect()->route('merchant.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
