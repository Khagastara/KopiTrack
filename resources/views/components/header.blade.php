{{-- <header class="bg-white shadow-sm z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <h1 class="text-xl font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
            </div>
            <div class="flex items-center">
                <div class="ml-3 relative">
                    <div class="flex items-center">
                        <button type="button"
                            class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown-500"
                            id="user-menu-button">
                            <span class="mr-3 text-gray-700">{{ Auth::user()->username }}</span>
                            <img class="h-8 w-8 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=random"
                                alt="Profile">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header> --}}
<header class="bg-white shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
        </div>
        <div class="flex items-center">
            <div class="relative">
                <button class="flex items-center text-gray-700 focus:outline-none">
                    <span class="mr-2">{{ Auth::user()->username ?? 'User' }}</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
