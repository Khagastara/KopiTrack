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

        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ], $request->filled('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->admin) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->merchant) {
                return redirect()->route('merchant.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Akun Anda tidak memiliki akses yang valid.',
                ])->withInput($request->only('username'));
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('merchant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
