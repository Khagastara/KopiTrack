<aside class="bg-white w-64 h-full shadow-md">
    <div class="mt-2 flex items-center">
        <div class="items-center justify-center flex w-full">
            <img src="{{ asset('images/Logo KopiTrack 2.0.png') }}" alt="KopiTrack Logo" class="h-24 w-auto">
        </div>
    </div>

    <nav class="mt-3 px-2">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ Auth::check() && Auth::user()->admin ? route('admin.dashboard') : route('merchant.dashboard') }}"
                class="{{ request()->routeIs('*.dashboard') ? 'bg-brown-100 text-brown-800' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
               group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-tachometer-alt mr-3 text-gray-500"></i>
                Dashboard
            </a>

            @if (Auth::check() && Auth::user()->admin)
                <a href="{{ route('merchants.index') }}"
                    class="{{ request()->routeIs('merchants.*') ? 'bg-brown-100 text-brown-800' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
               group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-store mr-3 text-gray-500"></i>
                    Kelola Merchant
                </a>
            @endif

            <!-- Produk -->
            <a href="{{ Auth::check() && Auth::user()->admin ? route('admin.product.index', 1) : route('merchant.product.index', 1) }}"
                class="{{ request()->routeIs('*.product.*') ? 'bg-brown-100 text-brown-800' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
                group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-mug-hot mr-3 text-gray-500"></i>
                Produk
            </a>

            <!-- Pesanan -->
            <a href="#"
                class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-shopping-cart mr-3 text-gray-500"></i>
                Pesanan
            </a>

            <!-- Keuangan -->
            @if (Auth::check() && Auth::user()->admin)
                <a href="{{ route('admin.finance.index') }}"
                    class="{{ request()->routeIs('admin.finance.*') ? 'bg-brown-100 text-brown-800' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}
                group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                    <i class="fas fa-money-bill-wave mr-3 text-gray-500"></i>
                    Keuangan
                </a>
            @endif

            <!-- Laporan -->
            <a href="#"
                class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-chart-bar mr-3 text-gray-500"></i>
                Laporan
            </a>

            <!-- Pengaturan -->
            <a href="#"
                class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 group flex items-center px-4 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-cog mr-3 text-gray-500"></i>
                Pengaturan
            </a>
        </div>
    </nav>

    <div class="absolute bottom-0 w-64 mb-6">
        <div class="px-6">

            <form action="{{ route('logout') }}" method="POST">

                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brown-600 hover:bg-brown-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</aside>
