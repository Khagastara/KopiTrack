@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Merchant -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-store fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Merchant</p>
                    <h3 class="font-bold text-2xl text-gray-800">{{ number_format($totalMerchant) }}</h3>
                    <p class="text-{{ $merchantGrowth >= 0 ? 'green' : 'red' }}-500 text-xs mt-1">
                        {{ $merchantGrowth >= 0 ? '+' : '' }}{{ $merchantGrowth }}% dari bulan lalu
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-chart-line fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <h3 class="font-bold text-2xl text-gray-800">
                        Rp {{ number_format($currentMonthIncome) }}
                    </h3>
                    <p class="text-{{ $incomeGrowth >= 0 ? 'green' : 'red' }}-500 text-xs mt-1">
                        {{ $incomeGrowth >= 0 ? '+' : '' }}{{ $incomeGrowth }}% dari bulan lalu
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-coffee fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <h3 class="font-bold text-2xl text-gray-800">{{ number_format($totalProducts) }}</h3>
                    <p class="text-{{ $productsGrowth >= 0 ? 'green' : 'red' }}-500 text-xs mt-1">
                        {{ $productsGrowth >= 0 ? '+' : '' }}{{ $productsGrowth }}% dari bulan lalu
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-users fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <h3 class="font-bold text-2xl text-gray-800">{{ number_format($totalUsers) }}</h3>
                    <p class="text-{{ $usersGrowth >= 0 ? 'green' : 'red' }}-500 text-xs mt-1">
                        {{ $usersGrowth >= 0 ? '+' : '' }}{{ $usersGrowth }}% dari bulan lalu
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Transaksi Terbaru -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Transaksi Terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Merchant
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($transaction->merchant->merchant_name ?? 'Unknown') }}&background=random"
                                            alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $transaction->merchant->merchant_name ?? 'Unknown Merchant' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $transaction->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($transaction->finance->income_balance ?? 0) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $transaction->finance->total_quantity ?? 0 }} items
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Belum ada transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Pertumbuhan Penjualan</h2>
                <div>
                    <select id="salesPeriod"
                        class="text-sm border-gray-300 rounded-md shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 focus:ring-opacity-50">
                        <option value="12">12 Bulan Terakhir</option>
                        <option value="6">6 Bulan Terakhir</option>
                        <option value="3">3 Bulan Terakhir</option>
                    </select>
                </div>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value);
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });

        document.getElementById('salesPeriod').addEventListener('change', function() {
            console.log('Periode dipilih:', this.value);
        });
    </script>
@endsection
