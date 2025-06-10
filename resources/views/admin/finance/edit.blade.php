<!-- filepath: d:\KopiTrack\resources\views\admin\finance\edit.blade.php -->
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
                <form method="POST" action="{{ route('admin.finance.edit', $finance->id) }}">
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

                    <div class="mb-4">
                        <label for="finance_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Rekapitulasi</label>
                        <input type="date" id="finance_date" name="finance_date" required
                            value="{{ old('finance_date', $finance->finance_date) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                    </div>

                    <div class="mb-4">
                        <label for="expenditure_balance" class="block text-sm font-medium text-gray-700 mb-1">Saldo
                            Pengeluaran (Rp)</label>
                        <input type="number" id="expenditure_balance" name="expenditure_balance" min="0"
                            step="1000" required
                            value="{{ old('expenditure_balance', $finance->expenditure_balance) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                        <p class="text-sm text-gray-500 mt-1">Masukkan pengeluaran untuk operasional, bahan baku, dll.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Pendapatan (Rp)</label>
                        <input type="text" value="Rp {{ number_format($finance->income_balance, 0, ',', '.') }}"
                            disabled class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                        <p class="text-sm text-gray-500 mt-1">Pendapatan dihitung otomatis dari transaksi pada tanggal
                            yang sama</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Produk Terjual</label>
                        <input type="text" value="{{ $finance->total_quantity }}" disabled
                            class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                        <p class="text-sm text-gray-500 mt-1">Dihitung otomatis dari transaksi pada tanggal yang sama
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700">
                            Update Data Keuangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
