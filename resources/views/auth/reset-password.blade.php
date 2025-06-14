@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">KopiTrack</h2>
                    <p class="text-gray-600 mt-2">Masukkan password baru Anda</p>
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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                            required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-black font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        Reset Password
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-brown-600 hover:text-brown-800">
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
