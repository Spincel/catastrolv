@extends('layouts.panel')

@section('content')

    <div class="mt-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Gestión de Usuarios</h2>
        <p class="text-gray-500 mt-1">Crear nuevos usuarios y gestionar existentes.</p>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 max-w-4xl">
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 bg-green-500 h-full"></div>
            
            <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="bg-green-100 text-green-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Crear Nuevo Usuario</h3>
                </div>
            </div>
            
            <form method="post" action="{{ route('users.store') }}" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nombre</label>
                    <input id="name" name="name" type="text" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autofocus autocomplete="name" />
                </div>

                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                    <input id="email" name="email" type="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autocomplete="email" />
                </div>

                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autocomplete="new-password" />
                </div>

                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required autocomplete="new-password" />
                </div>
                
                <div>
                    <label for="role" class="block font-medium text-sm text-gray-700">Rol</label>
                    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Lista de Usuarios</h2>
    </div>

    <div class="mt-8 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(isset($users))
                    @foreach ($users as $user_item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user_item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user_item->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user_item->role }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
