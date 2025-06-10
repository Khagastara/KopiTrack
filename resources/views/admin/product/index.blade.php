<x-app-layout title="Manajemen Produk">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Manajemen Produk</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product List Panel -->
                <div class="md:col-span-2 bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Produk</h3>
                    </div>

                    <div class="p-4">
                        <div class="mb-4">
                            <a href="{{ route('admin.product.create') }}"
                                class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                                <i class="fas fa-plus mr-1"></i> Tambah Produk
                            </a>
                        </div>

                        <!-- Products Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gambar</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Produk</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stok</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($productIndex as $product)
                                        <tr class="{{ $productShow->id == $product->id ? 'bg-brown-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ asset('storage/' . $product->product_image) }}"
                                                    alt="{{ $product->product_name }}"
                                                    class="h-12 w-12 object-cover rounded">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $product->product_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $product->product_quantity }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Rp
                                                    {{ number_format($product->product_price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.product.index', $product->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="text-green-600 hover:text-green-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada produk yang tersedia
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Product Detail Panel -->
                <div class="md:col-span-1 bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Detail Produk</h3>
                    </div>

                    <div class="p-4">
                        @if (isset($productShow))
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('storage/' . $productShow->product_image) }}"
                                    alt="{{ $productShow->product_name }}"
                                    class="h-48 w-48 object-cover rounded-lg mb-4">

                                <div class="w-full space-y-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">
                                            {{ $productShow->product_name }}</h4>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Stok:</span>
                                        <span class="text-sm font-medium">{{ $productShow->product_quantity }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Harga:</span>
                                        <span class="text-sm font-medium">Rp
                                            {{ number_format($productShow->product_price, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="pt-2">
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Deskripsi:</h5>
                                        <p class="text-sm text-gray-600">
                                            {{ $productShow->product_description ?: 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>

                                    <div class="pt-4 flex space-x-2">
                                        <a href="{{ route('admin.product.edit', $productShow->id) }}"
                                            class="w-full px-4 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700">
                                            Edit
                                        </a>
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
