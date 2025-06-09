<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(
            [
                'username' => $request->username,
                'password' => $request->password
            ]
            ))
            {
                $account = Auth::guard('web')->user();
                if ($account->admin) {
                    Auth::guard('admin')->login($account->owner);
                    return redirect()->route('admin.dashboard');
                }
                else {
                    Auth::guard('merchant')->login($account->merchant);
                    return redirect()->route('merchant.dashboard');
                }
            }

            return back()->withErrors([
                'username' => 'Username salah',
                'password' => 'Password salah'
            ])->withInput();
    }
}
