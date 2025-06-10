@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Statistik Card 1 -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-store fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Merchant</p>
                    <h3 class="font-bold text-2xl text-gray-800">124</h3>
                    <p class="text-green-500 text-xs mt-1">+8% dari bulan lalu</p>
                </div>
            </div>
        </div>

        <!-- Statistik Card 2 -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-chart-line fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <h3 class="font-bold text-2xl text-gray-800">Rp 24,5M</h3>
                    <p class="text-green-500 text-xs mt-1">+12% dari bulan lalu</p>
                </div>
            </div>
        </div>

        <!-- Statistik Card 3 -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-coffee fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <h3 class="font-bold text-2xl text-gray-800">587</h3>
                    <p class="text-green-500 text-xs mt-1">+5% dari bulan lalu</p>
                </div>
            </div>
        </div>

        <!-- Statistik Card 4 -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-users fa-fw fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <h3 class="font-bold text-2xl text-gray-800">2,450</h3>
                    <p class="text-green-500 text-xs mt-1">+15% dari bulan lalu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Merchant Terbaru -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Merchant Terbaru</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://ui-avatars.com/api/?name=Kopi+Kenangan&background=random"
                                            alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Kopi Kenangan</div>
                                        <div class="text-sm text-gray-500">kopi@kenangan.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">05/06/2025</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://ui-avatars.com/api/?name=Janji+Jiwa&background=random"
                                            alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Janji Jiwa</div>
                                        <div class="text-sm text-gray-500">info@janjijiwa.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">01/06/2025</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://ui-avatars.com/api/?name=Fore+Coffee&background=random"
                                            alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Fore Coffee</div>
                                        <div class="text-sm text-gray-500">contact@forecoffee.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">28/05/2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grafik Penjualan -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Pertumbuhan Penjualan</h2>
                <div>
                    <select
                        class="text-sm border-gray-300 rounded-md shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 focus:ring-opacity-50">
                        <option>30 Hari Terakhir</option>
                        <option>90 Hari Terakhir</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
            </div>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Grafik pertumbuhan penjualan di sini</p>
            </div>
        </div>
    </div>
@endsection
