<x-app-layout title="Tambah Produk">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h2>
                <a href="{{ route('admin.product.index', 1) }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Produk</label>
                            <input type="text" name="product_name" id="product_name"
                                value="{{ old('product_name') }}"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                        </div>

                        <div>
                            <label for="product_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Produk
                                (Rp)</label>
                            <input type="number" name="product_price" id="product_price"
                                value="{{ old('product_price') }}"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                        </div>

                        <div>
                            <label for="product_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stok
                                Produk</label>
                            <input type="number" name="product_quantity" id="product_quantity"
                                value="{{ old('product_quantity') }}"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                        </div>

                        <div>
                            <label for="product_image" class="block text-sm font-medium text-gray-700 mb-1">Gambar
                                Produk</label>
                            <input type="file" name="product_image" id="product_image"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maks: 5MB</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="product_description"
                                class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                            <textarea name="product_description" id="product_description" rows="4"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">{{ old('product_description') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit"
                                class="px-6 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                                Simpan Produk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
