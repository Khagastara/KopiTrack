<x-app-layout title="Sistem Kasir">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Sistem Kasir</h2>
                <a href="{{ route('merchant.transaction.index', 1) }}"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Product List Column (3/5 width) -->
                <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Produk</h3>

                    <!-- Product Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="productSearch" placeholder="Cari produk..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                            <div class="absolute left-3 top-2.5">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stok</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">
                                @forelse($products as $product)
                                    <tr class="product-item" data-name="{{ strtolower($product->product_name) }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $product->product_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                            {{ number_format($product->product_price, 0, ',', '.') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm {{ $product->product_quantity > 10 ? 'text-green-600' : 'text-yellow-600' }}">
                                            {{ $product->product_quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button type="button"
                                                class="add-product-btn px-3 py-1 bg-brown-600 text-white rounded-md hover:bg-brown-700"
                                                data-id="{{ $product->id }}" data-name="{{ $product->product_name }}"
                                                data-price="{{ $product->product_price }}"
                                                data-stock="{{ $product->product_quantity }}">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada produk tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shopping Cart Column (2/5 width) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Keranjang Belanja</h3>
                        <form action="{{ route('merchant.transaction.cart.clear') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt mr-1"></i> Kosongkan
                            </button>
                        </form>
                    </div>

                    <!-- Cart Items -->
                    <div class="mb-4 max-h-80 overflow-y-auto">
                        @if (count($cart) > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($cart as $productId => $item)
                                    <li class="py-3">
                                        <div class="flex justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $item['name'] }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    Rp {{ number_format($item['price'], 0, ',', '.') }} x
                                                    {{ $item['quantity'] }}
                                                </p>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-green-600 font-medium mr-4">
                                                    Rp
                                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </span>
                                                <form
                                                    action="{{ route('merchant.transaction.cart.remove', $productId) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            <form action="{{ route('merchant.transaction.cart.update') }}"
                                                method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                                <div class="flex rounded-md shadow-sm">
                                                    <button type="button"
                                                        class="quantity-btn-minus px-2 py-1 border border-r-0 border-gray-300 rounded-l-md bg-gray-50 text-gray-600">
                                                        -
                                                    </button>
                                                    <input type="number" name="quantity"
                                                        value="{{ $item['quantity'] }}" min="1" max="100"
                                                        class="w-14 border-y border-gray-300 py-1 text-center text-gray-700 focus:outline-none"
                                                        readonly>
                                                    <button type="button"
                                                        class="quantity-btn-plus px-2 py-1 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-600">
                                                        +
                                                    </button>
                                                </div>
                                                <button type="submit" class="ml-2 text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-center py-4">Keranjang belanja kosong</p>
                        @endif
                    </div>

                    <!-- Cart Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total:</span>
                            <span class="text-green-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- Checkout Button -->
                        <form action="{{ route('merchant.transaction.checkout') }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit"
                                class="w-full py-3 bg-brown-600 text-white rounded-md hover:bg-brown-700 flex items-center justify-center {{ count($cart) === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ count($cart) === 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cash-register mr-2"></i> Proses Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Cart Modal -->
    <div id="addToCartModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96">
            <h3 class="text-lg font-semibold mb-4">Tambah ke Keranjang</h3>
            <form action="{{ route('merchant.transaction.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" id="modal-product-id" name="product_id">

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Produk</p>
                    <p id="modal-product-name" class="font-medium"></p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Harga</p>
                    <p id="modal-product-price" class="font-medium text-green-600"></p>
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" id="modal-quantity" name="quantity" value="1" min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <p class="mt-1 text-xs text-gray-500">Stok tersedia: <span id="modal-product-stock"></span></p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500">Subtotal</p>
                    <p id="modal-subtotal" class="font-medium text-green-600"></p>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Product search functionality
                const searchInput = document.getElementById('productSearch');
                const productItems = document.querySelectorAll('.product-item');

                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();

                    productItems.forEach(item => {
                        const productName = item.dataset.name;
                        if (productName.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Add to cart modal
                const addToCartModal = document.getElementById('addToCartModal');
                const addProductBtns = document.querySelectorAll('.add-product-btn');
                const closeModalBtn = document.getElementById('closeModal');

                const modalProductId = document.getElementById('modal-product-id');
                const modalProductName = document.getElementById('modal-product-name');
                const modalProductPrice = document.getElementById('modal-product-price');
                const modalProductStock = document.getElementById('modal-product-stock');
                const modalQuantity = document.getElementById('modal-quantity');
                const modalSubtotal = document.getElementById('modal-subtotal');

                addProductBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const price = parseFloat(this.dataset.price);
                        const stock = parseInt(this.dataset.stock);

                        modalProductId.value = id;
                        modalProductName.textContent = name;
                        modalProductPrice.textContent = 'Rp ' + price.toLocaleString('id-ID');
                        modalProductStock.textContent = stock;
                        modalQuantity.value = 1;
                        modalQuantity.max = stock;
                        modalSubtotal.textContent = 'Rp ' + price.toLocaleString('id-ID');

                        addToCartModal.classList.remove('hidden');

                        // Update subtotal when quantity changes
                        modalQuantity.addEventListener('input', function() {
                            const qty = parseInt(this.value) || 1;
                            const subtotal = price * qty;
                            modalSubtotal.textContent = 'Rp ' + subtotal.toLocaleString(
                            'id-ID');
                        });
                    });
                });

                closeModalBtn.addEventListener('click', function() {
                    addToCartModal.classList.add('hidden');
                });

                // Quantity adjustment in cart
                document.querySelectorAll('.quantity-btn-minus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const input = this.nextElementSibling;
                        const value = parseInt(input.value);
                        if (value > 1) {
                            input.value = value - 1;
                        }
                    });
                });

                document.querySelectorAll('.quantity-btn-plus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const input = this.previousElementSibling;
                        const value = parseInt(input.value);
                        if (value < parseInt(input.getAttribute('max'))) {
                            input.value = value + 1;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
