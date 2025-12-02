@extends('layouts.panel')

@section('content')

    <header class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Configuración Institucional</h2>
        <p class="text-gray-500 mt-1">Personaliza la apariencia y enlaces del panel.</p>
    </header>

    @if (session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 bg-indigo-500 h-full"></div>
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-xs">1</span>
                    Identidad Visual
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre de la Institución</label>
                        <input type="text" name="institution_name" value="{{ \App\Models\Setting::get('institution_name', 'Catastro Municipal') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logotipo Institucional</label>
                        
                        @if(\App\Models\Setting::get('institution_logo'))
                            <div class="mb-3 p-2 bg-gray-100 rounded-lg inline-block">
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('institution_logo')) }}" class="h-16 w-auto">
                            </div>
                        @endif
                        
                        <input type="file" name="institution_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition"/>
                        <p class="text-xs text-gray-400 mt-1">Recomendado: PNG Transparente (200x200px)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 bg-pink-500 h-full"></div>
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-pink-100 text-pink-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-xs">2</span>
                    Redes y Contacto
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sitio Web Oficial</label>
                        <input type="url" name="link_website" value="{{ \App\Models\Setting::get('link_website') }}" placeholder="https://municipio.gob.mx" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Facebook URL</label>
                        <input type="url" name="link_facebook" value="{{ \App\Models\Setting::get('link_facebook') }}" placeholder="https://facebook.com/municipio" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Twitter / X URL</label>
                        <input type="url" name="link_twitter" value="{{ \App\Models\Setting::get('link_twitter') }}" placeholder="https://x.com/municipio" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-xl shadow-lg hover:bg-black transition transform hover:-translate-y-0.5">
                Guardar Configuración
            </button>
        </div>
    </form>

@endsection