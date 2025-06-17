<x-app-layout title="Manajemen Transaksi">
    @if (!$hasTransactions)
        <div class="alert alert-info">
            <h4 class="text-center">Belum ada data transaksi</h4>
        </div>
    @else
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Manajemen Transaksi</h2>

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

                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <input type="text" placeholder="Cari transaksi..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                            <div class="absolute left-3 top-2.5">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <button class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                            <i class="fas fa-download mr-1"></i> Export
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Merchant</th>
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
                            @forelse($transactions as $index => $transaction)
                                <tr class="{{ $transaction['id'] == $transactionIdDetail['id'] ? 'bg-brown-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction['transaction_date'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction['merchant_name'] }}</td>
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
                                        <a href="{{ route('admin.transaction.index', $transaction['id']) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data transaksi yang tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
