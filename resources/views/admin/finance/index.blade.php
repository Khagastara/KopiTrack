<x-app-layout title="Manajemen Keuangan">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Manajemen Keuangan</h2>

            <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-md">
                <a href="{{ route('admin.finance.index', ['current_date' => $navigationDate, 'direction' => 'prev']) }}"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-chevron-left mr-1"></i> Tahun Sebelumnya
                </a>
                <h3 class="text-xl font-semibold">Periode {{ $periodeStart }}</h3>
                <a href="{{ route('admin.finance.index', ['current_date' => $navigationDate, 'direction' => 'next']) }}"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    Tahun Berikutnya <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pendapatan</h3>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pengeluaran</h3>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpenditure, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Laba/Rugi</h3>
                    <p
                        class="text-2xl font-bold {{ $totalIncome - $totalExpenditure >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($totalIncome - $totalExpenditure, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Keuangan</h3>
                <canvas id="financeChart" height="120px"></canvas>
            </div>

            <div class="flex justify-between mb-4">
                <a href="{{ route('admin.finance.create') }}"
                    class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                    <i class="fas fa-plus mr-1"></i> Tambah Data Keuangan
                </a>

                <div>
                    <button id="filterButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-filter mr-1"></i> Filter Periode
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendapatan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengeluaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Produk Terjual</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Laba/Rugi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($finances as $finance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($finance->finance_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($finance->income_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($finance->expenditure_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $finance->total_quantity }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm {{ $finance->income_balance - $finance->expenditure_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp
                                    {{ number_format($finance->income_balance - $finance->expenditure_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.finance.edit', $finance->id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.show', $finance->id) }}"
                                        class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data keuangan yang tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96">
            <h3 class="text-lg font-semibold mb-4">Filter Data Keuangan</h3>
            <div class="space-y-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" id="start_date"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" id="end_date"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                </div>
                <div class="flex justify-end space-x-2">
                    <button id="closeFilterModal"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                    <button id="applyFilter"
                        class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('financeChart').getContext('2d');
                const financeChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($financeLabels) !!},
                        datasets: [{
                                label: 'Pendapatan',
                                data: {!! json_encode($financeIncome) !!},
                                backgroundColor: 'rgba(34, 197, 94, 0.5)',
                                borderColor: 'rgba(34, 197, 94, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! json_encode($financeExpenditure) !!},
                                backgroundColor: 'rgba(239, 68, 68, 0.5)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                const filterButton = document.getElementById('filterButton');
                const filterModal = document.getElementById('filterModal');
                const closeFilterModal = document.getElementById('closeFilterModal');
                const applyFilter = document.getElementById('applyFilter');

                filterButton.addEventListener('click', function() {
                    filterModal.classList.remove('hidden');
                });

                closeFilterModal.addEventListener('click', function() {
                    filterModal.classList.add('hidden');
                });

                applyFilter.addEventListener('click', function() {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;

                    if (!startDate || !endDate) {
                        alert('Silakan pilih tanggal mulai dan akhir');
                        return;
                    }

                    fetch(`/admin/finance/period?start_date=${startDate}&end_date=${endDate}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(
                                    `Hasil filter: Total Pendapatan: Rp${data.summary.total_income.toLocaleString()}, Total Pengeluaran: Rp${data.summary.total_expenditure.toLocaleString()}`
                                );
                                filterModal.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memfilter data');
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
