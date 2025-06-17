<header class="bg-white shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
        </div>
        <div class="flex items-center">
            <div class="relative">
                <button id="userDropdown" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md px-3 py-2 transition-colors duration-200">
                    <div class="flex items-center">
                        <div class="text-left">
                            <div class="text-sm font-medium">
                                @if(Auth::user()->admin)
                                    {{ Auth::user()->admin->admin_name }}
                                @elseif(Auth::user()->merchant)
                                    {{ Auth::user()->merchant->merchant_name }}
                                @else
                                    {{ Auth::user()->username ?? 'User' }}
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                @if(Auth::user()->admin)
                                    Administrator
                                @elseif(Auth::user()->merchant)
                                    Pedagang
                                @else
                                    User
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg class="h-5 w-5 ml-2 transition-transform duration-200" id="dropdownIcon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50 hidden">
                    <div class="py-1">
                        <!-- Profile Section -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if(Auth::user()->admin)
                                            {{ Auth::user()->admin->admin_name }}
                                        @elseif(Auth::user()->merchant)
                                            {{ Auth::user()->merchant->merchant_name }}
                                        @else
                                            {{ Auth::user()->username ?? 'User' }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        @if(Auth::user()->admin)
                            <a href="{{ route('admin.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                Profil Saya
                            </a>
                            <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-edit mr-3 text-gray-400"></i>
                                Edit Profil
                            </a>
                        @elseif(Auth::user()->merchant)
                            <a href="{{ route('merchant.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                Profil Saya
                            </a>
                            <a href="{{ route('merchant.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-edit mr-3 text-gray-400"></i>
                                Edit Profil
                            </a>
                        @endif

                        <div class="border-t border-gray-100 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('userDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownIcon = document.getElementById('dropdownIcon');

            dropdownButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const isHidden = dropdownMenu.classList.contains('hidden');

                if (isHidden) {
                    dropdownMenu.classList.remove('hidden');
                    dropdownIcon.style.transform = 'rotate(180deg)';
                } else {
                    dropdownMenu.classList.add('hidden');
                    dropdownIcon.style.transform = 'rotate(0deg)';
                }
            });

            document.addEventListener('click', function(e) {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    dropdownIcon.style.transform = 'rotate(0deg)';
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdownMenu.classList.add('hidden');
                    dropdownIcon.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
</header>
