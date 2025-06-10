<x-app-layout title="Detail Merchant">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Detail Merchant</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('merchants.edit', $merchant->id) }}"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('merchants.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Akun</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Username</span>
                                <p class="mt-1">{{ $merchant->account->username }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Email</span>
                                <p class="mt-1">{{ $merchant->account->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Merchant</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">ID Merchant</span>
                                <p class="mt-1">{{ $merchant->id }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Nama Merchant</span>
                                <p class="mt-1">{{ $merchant->merchant_name }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Nomor Telepon</span>
                                <p class="mt-1">{{ $merchant->phone_number }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Tanggal Bergabung</span>
                                <p class="mt-1">{{ $merchant->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end">
                        <form action="{{ route('merchants.destroy', $merchant->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus merchant ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                <i class="fas fa-trash mr-1"></i> Hapus Merchant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
