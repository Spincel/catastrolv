<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Catastro Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full">

@php
    use App\Models\Setting;
    $appLogo = Setting::get('institution_logo', 'img/logo.png'); // Fallback to default image
@endphp
<div class="flex min-h-full">
    
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 z-10 bg-white relative">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            
            <div>
                <img class="mx-auto h-24 w-auto" src="{{ asset('storage/' . $appLogo) }}" alt="Logo de la Aplicación">
                <h2 class="mt-6 text-3xl font-black tracking-tight text-gray-900">Bienvenido de nuevo</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sistema de Gestión Catastral y Ordenamiento
                </p>
            </div>

            <div class="mt-8">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Credenciales incorrectas</h3>
                                <div class="mt-1 text-xs text-red-700">
                                    Por favor verifica tu correo y contraseña.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6">
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-900">Correo Electrónico</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                       class="block w-full rounded-lg border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-medium bg-gray-50 focus:bg-white transition-all"
                                       placeholder="admin@catastro.com">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-900">Contraseña</label>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password" required
                                       class="block w-full rounded-lg border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-medium bg-gray-50 focus:bg-white transition-all"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900 cursor-pointer">Recordarme</label>
                            </div>

                            <div class="text-sm">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="font-bold text-indigo-600 hover:text-indigo-500">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-3 text-sm font-bold leading-6 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition transform hover:-translate-y-0.5">
                                Iniciar Sesión
                            </button>
                        </div>
                        
                        @if (Route::has('register'))
                            <p class="text-center text-sm text-gray-500 mt-6">
                                ¿No tienes cuenta?
                                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500">Regístrate aquí</a>
                            </p>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="relative hidden w-0 flex-1 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover" 
             src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" 
             alt="Fondo Catastro">
             
        <div class="absolute inset-0 bg-indigo-900/20 mix-blend-multiply"></div>
    </div>
    
</div>

</body>
</html>