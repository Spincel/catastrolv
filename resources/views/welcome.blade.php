<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="relative min-h-screen flex items-center justify-center selection:bg-indigo-500 selection:text-white">
        <div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg text-center">
            @if ($appLogo)
                <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo de la Aplicación" class="mx-auto h-24 mb-6">
            @else
                <svg class="mx-auto h-16 w-16 text-indigo-500 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Bienvenido al Sistema</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Gestiona tus trámites catastrales de forma eficiente.</p>

            <div class="space-y-4">
                <a href="{{ route('login') }}" class="inline-block w-full px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Acceso al sistema
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-block w-full px-6 py-3 border border-indigo-600 text-base font-medium rounded-md shadow-sm text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Solicitar usuario
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>