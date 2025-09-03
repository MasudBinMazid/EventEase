<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles (no Vite) -->
        <!-- If you already have a global CSS in public/assets/css, keep this line.
             If the filename is different, change it accordingly, or remove the line. -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        <!-- Tailwind (CDN) so your existing Tailwind classes render without a build step) -->
        <!-- If you already include Tailwind in style.css, you can remove this script. -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-8">
                <a href="/" class="block transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <x-application-logo class="w-32 h-32 drop-shadow-lg hover:drop-shadow-xl transition-all duration-300" style="filter: brightness(1.1) contrast(1.1);" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <!-- Scripts (optional; remove if you don't have these files) -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
    </body>
</html>
