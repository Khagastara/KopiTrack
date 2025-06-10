<x-app-layout title="Tambah Merchant">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Merchant Baru</h2>
                <a href="{{ route('merchants.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('merchants.store') }}">
                    @csrf

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Informasi Akun</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username
                                    <span class="text-red-600">*</span></label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}"
                                    required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                        class="text-red-600">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password
                                    <span class="text-red-600">*</span></label>
                                <input type="password" id="password" name="password" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span
                                        class="text-red-600">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Informasi Merchant</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="merchant_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Merchant <span class="text-red-600">*</span></label>
                                <input type="text" id="merchant_name" name="merchant_name"
                                    value="{{ old('merchant_name') }}" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                    Telepon <span class="text-red-600">*</span></label>
                                <input type="text" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                            Tambah Merchant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
