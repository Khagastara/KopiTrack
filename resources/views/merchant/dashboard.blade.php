@extends('layouts.dashboard')

@section('title', 'Merchant Dashboard')

@section('content')
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-chart-line fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Penjualan Hari Ini</p>
                    <h3 class="font-bold text-2xl text-gray-800">Rp 2,8jt</h3>
                    <p class="text-green-500 text-xs mt-1">+12% dari kemarin</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-shopping-basket fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Pesanan Hari Ini</p>
                    <h3 class="font-bold text-2xl text-gray-800">42</h3>
                    <p class="text-green-500 text-xs mt-1">+8% dari kemarin</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-coffee fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Produk Aktif</p>
                    <h3 class="font-bold text-2xl text-gray-800">24</h3>
                    <p class="text-gray-500 text-xs mt-1">Dari total 32 produk</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-star fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Rating Toko</p>
                    <h3 class="font-bold text-2xl text-gray-800">4.8/5.0</h3>
                    <p class="text-green-500 text-xs mt-1">+0.2 dari bulan lalu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Pesanan Terbaru</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ORD-7829</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Ahmad Budi</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Proses</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 75.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ORD-7828</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Siti Aminah</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 42.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ORD-7827</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Dian Purnama</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 108.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ORD-7826</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rudi Hartono</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 65.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Produk Terlaris</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">Kelola Produk</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terjual</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded">
                                        <!-- Gambar produk -->
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Kopi Susu Gula Aren</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Kopi Susu</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">128</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 3,2jt</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded">
                                        <!-- Gambar produk -->
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Americano</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Espresso</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">96</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 2,4jt</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded">
                                        <!-- Gambar produk -->
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Matcha Latte</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Non-Kopi</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">78</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp 1,95jt</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-4 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg">Grafik Penjualan</h2>
            <div>
                <select
                    class="text-sm border-gray-300 rounded-md shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 focus:ring-opacity-50">
                    <option>7 Hari Terakhir</option>
                    <option>30 Hari Terakhir</option>
                    <option>3 Bulan Terakhir</option>
                </select>
            </div>
        </div>
        <div class="h-72 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">Grafik penjualan harian di sini</p>
        </div>
    </div>
@endsection
