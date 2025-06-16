@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">KopiTrack</h2>
                    <p class="text-gray-600 mt-2">Masukkan kode OTP yang dikirim ke email Anda</p>
                    <p class="text-sm text-gray-500 mt-1">{{ session('email') }}</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify-otp') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div class="mb-6">
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input type="text" name="otp" id="otp" maxlength="6"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500 text-center text-2xl tracking-widest"
                            required autofocus placeholder="000000">
                        @error('otp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        Verifikasi OTP
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">Tidak menerima kode?</p>
                    <form method="POST" action="{{ route('password.resend-otp') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        <button type="submit" class="text-sm text-brown-600 hover:text-brown-800 underline">
                            Kirim ulang kode
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('otp').addEventListener('input', function() {
            if (this.value.length === 6) {
                setTimeout(() => {
                    this.form.submit();
                }, 500);
            }
        });
    </script>
@endsection
