<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopiTrack - @yield('title', 'Login')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>

<body class="bg-gray-100 h-screen">
    <div class="container mx-auto py-8">
        @yield('content')
    </div>
</body>

</html>
