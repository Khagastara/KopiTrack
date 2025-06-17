<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Detail Keuangan</h2>
                <a href="{{ route('admin.finance.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Data Keuangan Tanggal
                        {{ \Carbon\Carbon::parse($finance->finance_date)->format('d M Y') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Tanggal</h4>
                                <p class="text-base">
                                    {{ \Carbon\Carbon::parse($finance->finance_date)->format('d M Y') }}</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Total Produk Terjual</h4>
                                <p class="text-base">{{ $finance->total_quantity }} unit</p>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Pendapatan</h4>
                                <p class="text-base text-green-600 font-semibold">Rp
                                    {{ number_format($finance->income_balance, 0, ',', '.') }}</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Pengeluaran</h4>
                                <p class="text-base text-red-600 font-semibold">Rp
                                    {{ number_format($finance->expenditure_balance, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Laba/Rugi</h4>
                                <p
                                    class="text-lg {{ $finance->income_balance - $finance->expenditure_balance >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                    Rp
                                    {{ number_format($finance->income_balance - $finance->expenditure_balance, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button onclick="switchTab('pendapatan')" id="tab-pendapatan"
                                class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                <i class="fas fa-arrow-up mr-2"></i>
                                Pendapatan ({{ isset($transactionData) ? count($transactionData) : 0 }})
                            </button>
                            <button onclick="switchTab('pengeluaran')" id="tab-pengeluaran"
                                class="tab-button whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                                <i class="fas fa-arrow-down mr-2"></i>
                                Pengeluaran ({{ $finance->FinanceDetail->count() }})
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Contents -->
                    <div class="mt-6">
                        <!-- Pendapatan Tab -->
                        <div id="content-pendapatan" class="tab-content">
                            @if (isset($transactionData) && count($transactionData) > 0)
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-3">Daftar Transaksi</h4>
                                    <p class="text-sm text-gray-600 mb-4">Total transaksi: {{ count($transactionData) }}</p>
                                </div>

                                <div class="overflow-x-auto">
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
                                                    Pedagang</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Jumlah Produk</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Kuantitas</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($transactionData as $transaction)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction['id'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction['transaction_date'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction['merchant_name'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction['product_count'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction['quantity'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                                                        Rp {{ number_format($transaction['transaction_cost'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                                    Total Pendapatan:
                                                </td>
                                                <td class="px-6 py-4 text-sm font-bold text-green-600">
                                                    Rp {{ number_format($finance->income_balance, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-receipt text-gray-400 text-4xl mb-4"></i>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Transaksi</h4>
                                    <p class="text-gray-500">Tidak ada transaksi yang ditemukan untuk tanggal ini.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pengeluaran Tab -->
                        <div id="content-pengeluaran" class="tab-content hidden">
                            @if ($finance->FinanceDetail->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-3">Detail Pengeluaran</h4>
                                    <p class="text-sm text-gray-600 mb-4">Total item pengeluaran: {{ $finance->FinanceDetail->count() }}</p>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    No</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Deskripsi</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Biaya</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal Dibuat</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($finance->FinanceDetail as $index => $detail)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-900">
                                                        {{ $detail->expenditure_description }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">
                                                        Rp {{ number_format($detail->expenditure_cost, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $detail->created_at ? $detail->created_at->format('d M Y H:i') : '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                        <form action="{{ route('admin.finance.removeExpenditureDetail', $detail->id) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus item pengeluaran ini?')"
                                                              class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50">
                                                                <i class="fas fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                                    Total Pengeluaran:
                                                </td>
                                                <td class="px-6 py-4 text-sm font-bold text-red-600">
                                                    Rp {{ number_format($finance->expenditure_balance, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Add New Expenditure Form -->
                                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h5 class="text-md font-medium text-gray-900 mb-3">Tambah Pengeluaran Baru</h5>
                                    <form action="{{ route('admin.finance.addExpenditureDetail', $finance->id) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="md:col-span-2">
                                                <label for="expenditure_description" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Deskripsi
                                                </label>
                                                <input type="text"
                                                       name="expenditure_description"
                                                       id="expenditure_description"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="Masukkan deskripsi pengeluaran"
                                                       required>
                                            </div>
                                            <div>
                                                <label for="expenditure_cost" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Biaya
                                                </label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                                    <input type="number"
                                                           name="expenditure_cost"
                                                           id="expenditure_cost"
                                                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="0"
                                                           min="0"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Pengeluaran
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-money-bill-wave text-gray-400 text-4xl mb-4"></i>
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pengeluaran</h4>
                                    <p class="text-gray-500 mb-4">Belum ada detail pengeluaran untuk tanggal ini.</p>

                                    <div class="max-w-md mx-auto p-4 bg-gray-50 rounded-lg">
                                        <h5 class="text-md font-medium text-gray-900 mb-3">Tambah Pengeluaran Pertama</h5>
                                        <form action="{{ route('admin.finance.addExpenditureDetail', $finance->id) }}" method="POST">
                                            @csrf
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="expenditure_description_first" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Deskripsi
                                                    </label>
                                                    <input type="text"
                                                           name="expenditure_description"
                                                           id="expenditure_description_first"
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="Masukkan deskripsi pengeluaran"
                                                           required>
                                                </div>
                                                <div>
                                                    <label for="expenditure_cost_first" class="block text-sm font-medium text-gray-700 mb-1">
                                                        Biaya
                                                    </label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                                        <input type="number"
                                                               name="expenditure_cost"
                                                               id="expenditure_cost_first"
                                                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                               placeholder="0"
                                                               min="0"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit"
                                                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Tambah Pengeluaran
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex justify-between">
                    <a href="{{ route('admin.finance.edit', $finance->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            document.getElementById('content-' + tabName).classList.remove('hidden');

            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            activeButton.classList.add('border-blue-500', 'text-blue-600');
        }

        document.addEventListener('DOMContentLoaded', function() {
            switchTab('pendapatan');
        });
    </script>
</x-app-layout>
