<header class="bg-white shadow-sm z-10">
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
</header>
