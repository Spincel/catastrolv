@extends('layouts.panel')

@section('content')

    <header class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Configuración de Perfil</h2>
        <p class="text-gray-500 mt-1">Actualiza tu información personal y fortalece la seguridad de tu cuenta.</p>
    </header>

    <div class="grid grid-cols-1 gap-8 max-w-4xl">
        
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 bg-indigo-500 h-full"></div>
            
            <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="bg-indigo-100 text-indigo-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Información Básica</h3>
                    <p class="text-sm text-gray-500">Nombre y correo electrónico</p>
                </div>
            </div>
            
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 bg-emerald-500 h-full"></div>
            
             <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="bg-emerald-100 text-emerald-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Seguridad</h3>
                    <p class="text-sm text-gray-500">Actualizar contraseña</p>
                </div>
            </div>
            
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
             <div class="absolute top-0 left-0 w-1 bg-red-500 h-full"></div>
             
             <div class="flex items-center mb-6 border-b border-red-200 pb-4">
                <div class="bg-red-100 text-red-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-red-800">Borrar Cuenta</h3>
                    <p class="text-sm text-red-600">Acción irreversible</p>
                </div>
            </div>
            
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        @if (Auth::user()->role === 'admin')
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 bg-blue-500 h-full"></div>
            
            <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="bg-blue-100 text-blue-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h3>
                    <p class="text-sm text-gray-500">Administrar usuarios del sistema</p>
                </div>
            </div>
            
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                Gestionar Usuarios
            </a>
        </div>
        @endif
    </div>
@endsection