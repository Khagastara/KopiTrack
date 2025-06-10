@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">KopiTrack</h2>
                    <p class="text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                            required autofocus>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                            required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 text-brown-600 focus:ring-brown-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                        </div>
                        <a href="#" class="text-sm text-brown-600 hover:text-brown-800">Lupa password?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-black font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
