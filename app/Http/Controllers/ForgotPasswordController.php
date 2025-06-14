<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\Account;
use App\Mail\ForgotPasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:accounts,email',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan'
        ]);

        $user = Account::where('email', $request->email)->first();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::where('email', $request->email)->delete();

        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($request->email)->send(new ForgotPasswordOtp($otp, $user->username ?? 'User'));

            return redirect()->route('password.verify-otp')
                ->with('email', $request->email)
                ->with('success', 'Kode OTP telah dikirim ke email Anda');
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function showVerifyOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'email' => 'required|email'
        ], [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.digits' => 'Kode OTP harus 6 digit'
        ]);

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->otp)) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa');
        }

        $otpRecord->update(['verified' => true]);

        return redirect()->route('password.reset-form')
            ->with('email', $request->email)
            ->with('success', 'Kode OTP berhasil diverifikasi');
    }

    public function showResetForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }

        $otpRecord = PasswordResetOtp::where('email', session('email'))
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi telah berakhir. Silakan ulangi proses.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi tidak valid. Silakan ulangi proses.');
        }

        $user = Account::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $otpRecord->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->email ?? session('email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = Account::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Email tidak ditemukan');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'verified' => false
            ]
        );

        try {
            Mail::to($email)->send(new ForgotPasswordOtp($otp, $user->username ?? 'User'));

            return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP email: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
