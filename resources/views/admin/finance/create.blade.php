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

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="finance_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Rekapitulasi</label>
                        <select id="finance_date" name="finance_date" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                            <option value="">Pilih Tanggal</option>
                            @foreach ($tanggalRekapitulasi as $tanggal)
                                <option value="{{ $tanggal->transaction_date }}">
                                    {{ \Carbon\Carbon::parse($tanggal->transaction_date)->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="expenditure_balance" class="block text-sm font-medium text-gray-700 mb-1">Saldo
                            Pengeluaran (Rp)</label>
                        <input type="number" id="expenditure_balance" name="expenditure_balance" min="0"
                            step="1000" required value="{{ old('expenditure_balance', 0) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                        <p class="text-sm text-gray-500 mt-1">Masukkan pengeluaran untuk operasional, bahan baku, dll.
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                            Simpan Data Keuangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('financeForm');

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

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
                                window.location.href = '{{ route('admin.finance.index') }}';
                            } else {
                                let errorHtml = '<ul class="list-disc pl-5">';
                                for (const [key, value] of Object.entries(data.errors)) {
                                    errorHtml += `<li>${value}</li>`;
                                }
                                errorHtml += '</ul>';

                                const errorDiv = document.createElement('div');
                                errorDiv.classList.add('mb-4', 'p-4', 'bg-red-100', 'border',
                                    'border-red-400', 'text-red-700', 'rounded-md');
                                errorDiv.innerHTML = errorHtml;

                                const existingError = document.querySelector('.bg-red-100');
                                if (existingError) {
                                    existingError.remove();
                                }

                                form.prepend(errorDiv);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>
