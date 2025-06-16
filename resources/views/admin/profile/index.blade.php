<x-app-layout title="Profil Admin">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Profil Admin</h2>
                <a href="{{ route('admin.profile.edit') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-edit mr-1"></i> Edit Profil
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Profil</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Username
                            </label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-900">
                                {{ $admin->account->username }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-900">
                                {{ $admin->account->email }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Admin
                            </label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-900">
                                {{ $admin->admin_name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon
                            </label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-900">
                                {{ $admin->phone_number ?? 'Tidak diatur' }}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
