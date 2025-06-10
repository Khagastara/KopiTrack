<x-app-layout title="Manajemen Transaksi">
    @if (!$hasTransactions)
        <div class="alert alert-info">
            <h4 class="text-center">Belum ada data transaksi</h4>
        </div>
    @else
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Transaksi Saya</h2>

                <!-- Detail Transaksi Card -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Transaksi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-500">ID Transaksi</p>
                            <p class="font-medium">{{ $transactionIdDetail['id'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Transaksi</p>
                            <p class="font-medium">{{ $transactionIdDetail['transaction_date'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Merchant</p>
                            <p class="font-medium">{{ $transactionIdDetail['merchant_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Produk</p>
                            <p class="font-medium">{{ count($transactionIdDetail['product_details']) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Kuantitas</p>
                            <p class="font-medium">{{ $transactionIdDetail['quantity'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Biaya</p>
                            <p class="font-medium text-green-600">
                                Rp {{ number_format($transactionIdDetail['transaction_cost'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    @if (count($transactionIdDetail['product_details']) > 1)
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-700 mb-2">Daftar Produk:</h4>
                            <div class="bg-gray-50 rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nama
                                                Produk
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kuantitas
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($transactionIdDetail['product_details'] as $detail)
                                            <tr>
                                                <td class="px-4 py-2 text-sm">{{ $detail['product_name'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $detail['quantity'] }}</td>
                                                <td class="px-4 py-2 text-sm">Rp
                                                    {{ number_format($detail['price'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-6">
                    <a href="{{ route('merchant.transaction.create.form') }}"
                        class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                        <i class="fas fa-plus mr-1"></i> Buat Transaksi Baru
                    </a>
                </div>
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kuantitas</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Biaya</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr
                                    class="{{ $transaction['id'] == $transactionIdDetail['id'] ? 'bg-brown-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction['id'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction['transaction_date'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if (isset($transaction['product_details']) && count($transaction['product_details']) > 1)
                                            <div class="flex items-center">
                                                <span>{{ $transaction['product_name'] }}</span>
                                                <span
                                                    class="ml-2 text-xs bg-gray-100 text-gray-800 py-1 px-2 rounded-full">+{{ count($transaction['product_details']) - 1 }}
                                                    produk lainnya</span>
                                            </div>
                                        @else
                                            {{ $transaction['product_name'] }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                        Rp {{ number_format($transaction['transaction_cost'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('merchant.transaction.index', $transaction['id']) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data transaksi yang tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
