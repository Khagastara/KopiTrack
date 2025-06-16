<x-app-layout title="Manajemen Keuangan">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Edit Data Keuangan</h2>
                <a href="{{ route('admin.finance.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form method="POST" action="{{ route('admin.finance.edit', $finance->id) }}" id="financeForm">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="finance_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Rekapitulasi</label>
                            <input type="date" id="finance_date" name="finance_date" required
                                value="{{ old('finance_date', $finance->finance_date) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Produk Terjual</label>
                            <input type="text" value="{{ $finance->total_quantity }} unit" disabled
                                class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                            <p class="text-sm text-gray-500 mt-1">Dihitung otomatis dari transaksi pada tanggal yang sama</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Pendapatan</label>
                            <input type="text" value="Rp {{ number_format($finance->income_balance, 0, ',', '.') }}"
                                disabled class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                            <p class="text-sm text-gray-500 mt-1">Pendapatan dihitung otomatis dari transaksi</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Pengeluaran</label>
                            <input type="text" id="total_expenditure"
                                value="Rp {{ number_format($finance->expenditure_balance, 0, ',', '.') }}"
                                disabled class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                            <p class="text-sm text-gray-500 mt-1">Dihitung otomatis dari detail pengeluaran</p>
                        </div>
                    </div>

                    <!-- Expenditure Details Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Detail Pengeluaran</h3>
                            <button type="button" onclick="addExpenditureRow()"
                                class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Item
                            </button>
                        </div>

                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b">
                                <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                                    <div class="col-span-1">No</div>
                                    <div class="col-span-6">Deskripsi Pengeluaran</div>
                                    <div class="col-span-3">Biaya (Rp)</div>
                                    <div class="col-span-2 text-center">Aksi</div>
                                </div>
                            </div>

                            <div id="expenditure-container" class="divide-y divide-gray-200">
                                @foreach ($finance->FinanceDetail as $index => $detail)
                                    <div class="expenditure-row bg-white px-4 py-3" data-index="{{ $index }}">
                                        <div class="grid grid-cols-12 gap-4 items-center">
                                            <div class="col-span-1">
                                                <span class="row-number text-sm text-gray-600">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="col-span-6">
                                                <input type="text"
                                                    name="expenditure_details[{{ $index }}][description]"
                                                    value="{{ old('expenditure_details.' . $index . '.description', $detail->expenditure_description) }}"
                                                    placeholder="Masukkan deskripsi pengeluaran"
                                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                    required>
                                            </div>
                                            <div class="col-span-3">
                                                <input type="number"
                                                    name="expenditure_details[{{ $index }}][cost]"
                                                    value="{{ old('expenditure_details.' . $index . '.cost', $detail->expenditure_cost) }}"
                                                    placeholder="0"
                                                    min="0"
                                                    step="1000"
                                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm expenditure-cost"
                                                    required
                                                    onchange="updateTotalExpenditure()">
                                            </div>
                                            <div class="col-span-2 text-center">
                                                <button type="button" onclick="removeExpenditureRow(this)"
                                                    class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($finance->FinanceDetail->count() == 0)
                                <div id="no-expenditure" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-money-bill-wave text-3xl mb-2"></i>
                                    <p>Belum ada detail pengeluaran</p>
                                    <p class="text-sm">Klik "Tambah Item" untuk menambah pengeluaran</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Pengeluaran:</span>
                                <span id="total-display" class="text-lg font-bold text-red-600">
                                    Rp {{ number_format($finance->expenditure_balance, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Ringkasan Keuangan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Pendapatan:</span>
                                <p class="font-semibold text-green-600">Rp {{ number_format($finance->income_balance, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Pengeluaran:</span>
                                <p id="summary-expenditure" class="font-semibold text-red-600">
                                    Rp {{ number_format($finance->expenditure_balance, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Laba/Rugi:</span>
                                <p id="summary-profit" class="font-semibold {{ $finance->income_balance - $finance->expenditure_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($finance->income_balance - $finance->expenditure_balance, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Update Data Keuangan
                        </button>
                        <a href="{{ route('admin.finance.show', $finance->id) }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let expenditureIndex = {{ $finance->FinanceDetail->count() }};
        const incomeBalance = {{ $finance->income_balance }};

        function addExpenditureRow() {
            const container = document.getElementById('expenditure-container');
            const noExpenditureDiv = document.getElementById('no-expenditure');

            if (noExpenditureDiv) {
                noExpenditureDiv.remove();
            }

            const rowDiv = document.createElement('div');
            rowDiv.className = 'expenditure-row bg-white px-4 py-3';
            rowDiv.setAttribute('data-index', expenditureIndex);

            rowDiv.innerHTML = `
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-1">
                        <span class="row-number text-sm text-gray-600">${expenditureIndex + 1}</span>
                    </div>
                    <div class="col-span-6">
                        <input type="text"
                            name="expenditure_details[${expenditureIndex}][description]"
                            placeholder="Masukkan deskripsi pengeluaran"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                            required>
                    </div>
                    <div class="col-span-3">
                        <input type="number"
                            name="expenditure_details[${expenditureIndex}][cost]"
                            placeholder="0"
                            min="0"
                            step="1000"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm expenditure-cost"
                            required
                            onchange="updateTotalExpenditure()">
                    </div>
                    <div class="col-span-2 text-center">
                        <button type="button" onclick="removeExpenditureRow(this)"
                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(rowDiv);
            expenditureIndex++;
            updateRowNumbers();
        }

        function removeExpenditureRow(button) {
            const row = button.closest('.expenditure-row');
            row.remove();
            updateRowNumbers();
            updateTotalExpenditure();

            const container = document.getElementById('expenditure-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div id="no-expenditure" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-money-bill-wave text-3xl mb-2"></i>
                        <p>Belum ada detail pengeluaran</p>
                        <p class="text-sm">Klik "Tambah Item" untuk menambah pengeluaran</p>
                    </div>
                `;
            }
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('.expenditure-row');
            rows.forEach((row, index) => {
                const numberSpan = row.querySelector('.row-number');
                numberSpan.textContent = index + 1;

                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.name;
                    if (name.includes('expenditure_details[')) {
                        const newName = name.replace(/expenditure_details\[\d+\]/, `expenditure_details[${index}]`);
                        input.name = newName;
                    }
                });

                row.setAttribute('data-index', index);
            });
        }

        function updateTotalExpenditure() {
            const costInputs = document.querySelectorAll('.expenditure-cost');
            let total = 0;

            costInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_expenditure').value = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('summary-expenditure').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const profit = incomeBalance - total;
            const profitElement = document.getElementById('summary-profit');
            profitElement.textContent = 'Rp ' + profit.toLocaleString('id-ID');
            profitElement.className = profit >= 0 ? 'font-semibold text-green-600' : 'font-semibold text-red-600';
        }

        document.getElementById('financeForm').addEventListener('submit', function(e) {
            const expenditureRows = document.querySelectorAll('.expenditure-row');

            if (expenditureRows.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 detail pengeluaran');
                return false;
            }

            let hasEmptyFields = false;
            expenditureRows.forEach(row => {
                const descInput = row.querySelector('input[name*="[description]"]');
                const costInput = row.querySelector('input[name*="[cost]"]');

                if (!descInput.value.trim() || !costInput.value.trim()) {
                    hasEmptyFields = true;
                }
            });

            if (hasEmptyFields) {
                e.preventDefault();
                alert('Semua field deskripsi dan biaya harus diisi');
                return false;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateTotalExpenditure();
        });
    </script>
</x-app-layout>
