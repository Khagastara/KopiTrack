<x-app-layout title="Manajemen Keuangan">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambah Data Keuangan</h2>
                <a href="{{ route('admin.finance.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form id="financeForm" method="POST" action="{{ route('admin.finance.create') }}">
                    @csrf

                    <div id="errorContainer"></div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label for="finance_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rekapitulasi</label>
                        <div class="relative">
                            <input type="date" id="finance_date" name="finance_date"
                                value="{{ now()->format('Y-m-d') }}"
                                readonly
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-600 cursor-not-allowed focus:ring-0 focus:border-gray-300">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Tanggal rekapitulasi otomatis disetel ke hari ini ({{ now()->format('d M Y') }})</p>
                    </div>

                    <!-- Detail Pengeluaran Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Pengeluaran</h3>
                            <button type="button" id="addExpenditureBtn"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-plus mr-1"></i> Tambah Pengeluaran
                            </button>
                        </div>

                        <div id="expenditureContainer" class="space-y-4">
                            <!-- Initial expenditure item -->
                            <div class="expenditure-item bg-gray-50 p-4 rounded-lg border">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-md font-medium text-gray-800">Pengeluaran #1</h4>
                                    <button type="button" class="remove-expenditure text-red-600 hover:text-red-800 hidden">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengeluaran</label>
                                        <input type="text" name="expenditure_details[0][description]"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500"
                                            placeholder="Contoh: Beli bahan baku, Transport, dll."
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                                        <input type="number" name="expenditure_details[0][cost]"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 expenditure-cost"
                                            placeholder="0" min="0" step="1000" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Expenditure Display -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Pengeluaran:</span>
                                <span id="totalExpenditure" class="text-lg font-bold text-blue-600">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="submitBtn"
                            class="w-full px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Simpan Data Keuangan</span>
                            <i id="submitLoader" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .expenditure-item {
            transition: all 0.3s ease;
        }

        .expenditure-item:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-out {
            animation: fadeOut 0.3s ease-out;
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('financeForm');
                const expenditureContainer = document.getElementById('expenditureContainer');
                const addExpenditureBtn = document.getElementById('addExpenditureBtn');
                const totalExpenditureEl = document.getElementById('totalExpenditure');
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const submitLoader = document.getElementById('submitLoader');

                let expenditureCount = 1;

                // Function to format currency
                function formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                }

                // Function to calculate total expenditure
                function calculateTotal() {
                    const costInputs = document.querySelectorAll('.expenditure-cost');
                    let total = 0;

                    costInputs.forEach(input => {
                        const value = parseFloat(input.value) || 0;
                        total += value;
                    });

                    totalExpenditureEl.textContent = formatCurrency(total);
                }

                // Function to update item numbers
                function updateItemNumbers() {
                    const items = document.querySelectorAll('.expenditure-item');
                    items.forEach((item, index) => {
                        const title = item.querySelector('h4');
                        title.textContent = `Pengeluaran #${index + 1}`;

                        // Update input names
                        const inputs = item.querySelectorAll('input');
                        inputs.forEach(input => {
                            if (input.name.includes('[description]')) {
                                input.name = `expenditure_details[${index}][description]`;
                            } else if (input.name.includes('[cost]')) {
                                input.name = `expenditure_details[${index}][cost]`;
                            }
                        });

                        // Show/hide remove button
                        const removeBtn = item.querySelector('.remove-expenditure');
                        if (items.length > 1) {
                            removeBtn.classList.remove('hidden');
                        } else {
                            removeBtn.classList.add('hidden');
                        }
                    });
                }

                // Add expenditure item
                addExpenditureBtn.addEventListener('click', function() {
                    const newItem = document.createElement('div');
                    newItem.className = 'expenditure-item bg-gray-50 p-4 rounded-lg border fade-in';

                    newItem.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-md font-medium text-gray-800">Pengeluaran #${expenditureCount + 1}</h4>
                            <button type="button" class="remove-expenditure text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengeluaran</label>
                                <input type="text" name="expenditure_details[${expenditureCount}][description]"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500"
                                    placeholder="Contoh: Beli bahan baku, Transport, dll."
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                                <input type="number" name="expenditure_details[${expenditureCount}][cost]"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 expenditure-cost"
                                    placeholder="0" min="0" step="1000" required>
                            </div>
                        </div>
                    `;

                    expenditureContainer.appendChild(newItem);
                    expenditureCount++;
                    updateItemNumbers();

                    // Add event listener for the new cost input
                    const newCostInput = newItem.querySelector('.expenditure-cost');
                    newCostInput.addEventListener('input', calculateTotal);

                    // Add event listener for remove button
                    const removeBtn = newItem.querySelector('.remove-expenditure');
                    removeBtn.addEventListener('click', function() {
                        newItem.classList.add('fade-out');
                        setTimeout(() => {
                            newItem.remove();
                            updateItemNumbers();
                            calculateTotal();
                        }, 300);
                    });
                });

                // Event delegation for remove buttons and cost inputs
                expenditureContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-expenditure')) {
                        const item = e.target.closest('.expenditure-item');
                        item.classList.add('fade-out');
                        setTimeout(() => {
                            item.remove();
                            updateItemNumbers();
                            calculateTotal();
                        }, 300);
                    }
                });

                expenditureContainer.addEventListener('input', function(e) {
                    if (e.target.classList.contains('expenditure-cost')) {
                        calculateTotal();
                    }
                });

                // Form submission with loading state
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Disable submit button and show loader
                    submitBtn.disabled = true;
                    submitText.textContent = 'Menyimpan...';
                    submitLoader.classList.remove('hidden');

                    // Clear previous errors
                    const errorContainer = document.getElementById('errorContainer');
                    errorContainer.innerHTML = '';

                    const formData = new FormData(form);

                    fetch('{{ route('admin.finance.create') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message briefly before redirect
                                submitText.textContent = 'Berhasil!';
                                submitLoader.classList.add('hidden');

                                setTimeout(() => {
                                    window.location.href = '{{ route('admin.finance.index') }}';
                                }, 1000);
                            } else {
                                // Reset button state
                                submitBtn.disabled = false;
                                submitText.textContent = 'Simpan Data Keuangan';
                                submitLoader.classList.add('hidden');

                                // Show errors
                                let errorHtml = '<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">';
                                errorHtml += '<ul class="list-disc pl-5">';

                                if (data.errors) {
                                    for (const [key, messages] of Object.entries(data.errors)) {
                                        if (Array.isArray(messages)) {
                                            messages.forEach(message => {
                                                errorHtml += `<li>${message}</li>`;
                                            });
                                        } else {
                                            errorHtml += `<li>${messages}</li>`;
                                        }
                                    }
                                } else if (data.message) {
                                    errorHtml += `<li>${data.message}</li>`;
                                }

                                errorHtml += '</ul></div>';
                                errorContainer.innerHTML = errorHtml;

                                // Scroll to error
                                errorContainer.scrollIntoView({ behavior: 'smooth' });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Reset button state
                            submitBtn.disabled = false;
                            submitText.textContent = 'Simpan Data Keuangan';
                            submitLoader.classList.add('hidden');

                            // Show generic error
                            const errorContainer = document.getElementById('errorContainer');
                            errorContainer.innerHTML = `
                                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                                    <p>Terjadi kesalahan saat menyimpan data. Silakan coba lagi.</p>
                                </div>
                            `;
                        });
                });

                // Initial calculation
                calculateTotal();
                updateItemNumbers();
            });
        </script>
    @endpush
</x-app-layout>
