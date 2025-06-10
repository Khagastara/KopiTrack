<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KopiTrack - {{ $title ?? 'Dashboard' }}</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configure Tailwind through the script -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50: '#fdf8f6',
                            100: '#f2e8e5',
                            200: '#eaddda',
                            300: '#e0cec7',
                            400: '#d2bab0',
                            500: '#a18072',
                            600: '#845d51',
                            700: '#694a3e',
                            800: '#51392e',
                            900: '#3d2c22',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar Component -->
        <x-sidebar />

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header Component -->
            @include('components.header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
