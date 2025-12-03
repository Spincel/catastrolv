<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catastro Municipal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <aside id="sidebar" class="w-64 bg-[#0f172a] text-white flex-shrink-0 hidden lg:flex flex-col shadow-2xl transition-all duration-300">
        <div class="p-6 border-b border-gray-800">
            <h1 class="text-xl font-bold leading-tight">Catastro<br><span class="text-indigo-500">Santiago</span></h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 flex flex-col overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-lg text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Inicio</span>
            </a>
            
            <a href="{{ route('official_numbers.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('official_numbers.index') ? 'bg-indigo-600 shadow-lg text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="font-medium">Nuevo Registro</span>
            </a>

            <a href="{{ route('official_numbers.list') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('official_numbers.list') ? 'bg-indigo-600 shadow-lg text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path></svg>
                <span class="font-medium">Consultar Todos</span>
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('reports.index') ? 'bg-indigo-600 shadow-lg text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4a4 4 0 014-4h.875a4 4 0 014 4V17m-4-10l-2 2-2-2"></path></svg>
                <span class="font-medium">Reportes</span>
            </a>

            <div class="mt-auto pt-4 border-t border-gray-800">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="group relative flex-shrink-0">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-700 group-hover:border-indigo-500 transition-colors"
                             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&size=128" alt="Avatar">
                    </a>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <a href="{{ route('profile.edit') }}" class="text-xs text-gray-400 hover:text-indigo-400 transition truncate block">Editar perfil</a>
                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('settings.edit') }}" class="text-xs text-gray-400 hover:text-indigo-400 transition truncate block mt-1" title="Configuración Institucional">
                                <svg class="w-4 h-4 inline-block align-middle mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Configuración Institucional
                            </a>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-gray-800 rounded-lg transition" title="Cerrar Sesión">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-100 relative flex flex-col">
        
        <header class="bg-white h-16 border-b border-gray-200 flex items-center px-6 sticky top-0 z-30 shadow-sm">
            <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <span class="ml-4 font-semibold text-gray-500 text-sm">Panel de Control</span>
            <div id="live-clock" class="ml-auto text-gray-700 text-sm font-semibold"></div>
        </header>

        <div class="p-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>

    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const formattedDate = now.toLocaleDateString('es-ES', dateOptions);
            const formattedTime = now.toLocaleTimeString('es-ES', timeOptions);
            document.getElementById('live-clock').textContent = `${formattedDate}, ${formattedTime}`;
        }

        setInterval(updateClock, 1000);
        updateClock(); // Initial call to display the clock immediately

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('lg:flex')) {
                sidebar.classList.remove('lg:flex');
                sidebar.classList.add('hidden');
            } else {
                sidebar.classList.add('lg:flex');
                sidebar.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>