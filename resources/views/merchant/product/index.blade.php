<x-app-layout title="Produk">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Kami</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product List Panel -->
                <div class="md:col-span-2 bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Katalog Produk</h3>
                    </div>

                    <div class="p-4">
                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($productIndex as $product)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow {{ $productShow->id == $product->id ? 'ring-2 ring-brown-500' : '' }}">
                                    <a href="{{ route('merchant.product.index', $product->id) }}">
                                        <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" class="h-40 w-full object-cover">
                                        <div class="p-3">
                                            <h4 class="font-semibold text-gray-800">{{ $product->product_name }}</h4>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-sm text-gray-600">Stok: {{ $product->product_quantity }}</span>
                                                <span class="text-brown-600 font-medium">Rp {{ number_format($product->product_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-6 text-gray-500">
                                    Tidak ada produk yang tersedia
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Product Detail Panel -->
                <div class="md:col-span-1 bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Detail Produk</h3>
                    </div>

                    <div class="p-4">
                        @if(isset($productShow))
                            <div class="flex flex-col items-center">
                                <img src="{{ asset($productShow->product_image) }}" alt="{{ $productShow->product_name }}" class="h-48 w-48 object-cover rounded-lg mb-4">

                                <div class="w-full space-y-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $productShow->product_name }}</h4>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Stok:</span>
                                        <span class="text-sm font-medium">{{ $productShow->product_quantity }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Harga:</span>
                                        <span class="text-sm font-medium">Rp {{ number_format($productShow->product_price, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="pt-2">
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Deskripsi:</h5>
                                        <p class="text-sm text-gray-600">
                                            {{ $productShow->product_description ?: 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>

                                    <div class="pt-4">
                                        <button class="w-full px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                                            Tambahkan ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-6">
                                Pilih produk untuk melihat detail
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
