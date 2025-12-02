@extends('layouts.panel')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Base de Datos de Registros</h1>
            <p class="text-gray-500 mt-1">Gestión completa de expedientes catastrales.</p>
        </div>
        
        <a href="{{ route('official_numbers.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Trámite
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            
            <form action="{{ route('official_numbers.list') }}" method="GET" class="flex flex-col xl:flex-row gap-4 justify-between items-center">
                
                <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                    
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar folio, nombre..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-500 uppercase">Del:</span>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="block w-full md:w-auto border border-gray-300 rounded-xl text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-600">
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-500 uppercase">Al:</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="block w-full md:w-auto border border-gray-300 rounded-xl text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-600">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-black text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filtrar
                    </button>
                    
                    @if(request('search') || request('date_from') || request('date_to'))
                        <a href="{{ route('official_numbers.list') }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:text-red-500 hover:border-red-300 rounded-xl text-sm font-bold shadow-sm transition" title="Quitar filtros">
                            ✕
                        </a>
                    @endif
                </div>

                <div class="text-sm font-bold text-gray-500 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm whitespace-nowrap">
                    Resultados: <span class="text-indigo-600 text-lg">{{ $numbers->total() }}</span>
                </div>
            </form>
        </div>

            <div class="text-sm font-bold text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                Total: <span class="text-indigo-600">{{ $numbers->total() }}</span> expedientes
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Folio Oficial</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Propietario / Ubicación</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Docs</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha / Tesorería</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($numbers as $number)
                        @php
                            // Lógica para contar documentos subidos
                            $docsCount = collect([
                                $number->doc_escrituras, 
                                $number->doc_constancia, 
                                $number->doc_ine, 
                                $number->doc_ine_reverso, 
                                $number->doc_predial
                            ])->filter()->count();
                            
                            // Color del badge según cantidad
                            $badgeColor = $docsCount >= 4 ? 'bg-green-100 text-green-700 border-green-200' : ($docsCount > 0 ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : 'bg-gray-100 text-gray-400 border-gray-200');
                        @endphp

                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-bold border border-indigo-100">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-indigo-700">{{ $number->official_number }}</div>
                                        <div class="text-xs text-gray-500">{{ $number->property_type == 'vivienda' ? 'Casa Habitación' : 'Comercial' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $number->owner_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ \Illuminate\Support\Str::limit($number->street_name . ' #' . $number->ext_number, 35) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                    {{ $docsCount }} / 5
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($number->assignment_date)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">Pago: {{ $number->treasury_office_number }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    
                                    <a href="{{ route('official_numbers.explore', $number->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition shadow-sm" title="Visualizar Detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="{{ route('official_numbers.pdf', $number->id) }}" target="_blank" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm" title="Descargar PDF Oficial">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 p-4 rounded-full mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">No se encontraron registros.</p>
                                    <a href="{{ route('official_numbers.index') }}" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm font-bold">Crear el primero &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($numbers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $numbers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

@endsection