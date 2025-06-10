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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>

                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex justify-between">
                    <a href="{{ route('admin.finance.edit', $finance->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>

                    <form action="{{ route('admin.finance.destroy', $finance->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
