@extends('layouts.panel')

@section('content')

    <header class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Reportes de Números Oficiales</h2>
        <p class="text-gray-500 mt-1">Genera y exporta reportes de números oficiales y visualiza estadísticas.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 bg-indigo-500 h-full"></div>
                
                <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                    <div class="bg-indigo-100 text-indigo-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Filtros de Reporte</h3>
                    </div>
                </div>
                
                <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Fecha de inicio</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block font-medium text-sm text-gray-700">Fecha de fin</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="month" class="block font-medium text-sm text-gray-700">Mes</label>
                            <select name="month" id="month" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todos</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="property_type" class="block font-medium text-sm text-gray-700">Uso de Predio</label>
                            <select name="property_type" id="property_type" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todos</option>
                                <option value="vivienda" {{ request('property_type') == 'vivienda' ? 'selected' : '' }}>Vivienda</option>
                                <option value="comercial" {{ request('property_type') == 'comercial' ? 'selected' : '' }}>Comercial</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="colonia" class="block font-medium text-sm text-gray-700">Colonia</label>
                            <input type="text" name="colonia" id="colonia" value="{{ request('colonia') }}" placeholder="Buscar por colonia" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-4 mt-6">
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:border-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            Limpiar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            Filtrar
                        </button>
                        <a href="{{ route('reports.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Exportar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 bg-teal-500 h-full"></div>
            <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="bg-teal-100 text-teal-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Estadísticas por Colonia</h3>
                </div>
            </div>
            <canvas id="suburbChart"></canvas>
        </div>

    </div>

    <div class="mt-8 bg-white border border-gray-200 rounded-xl p-8 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 bg-purple-500 h-full"></div>
        
        <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
            <div class="bg-purple-100 text-purple-700 w-10 h-10 rounded-full flex items-center justify-center mr-4 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4a4 4 0 014-4h.875a4 4 0 014 4V17m-4-10l-2 2-2-2"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Resultados del Reporte</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Folio Oficial</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Propietario / Ubicación</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Uso de Predio</th>
                        <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha / Tesorería</th>
                        <th scope="col" class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($numbers as $number)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition duration-150 ease-in-out group">
                            <td class="px-4 py-2">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-md bg-indigo-50 text-indigo-600 font-bold border border-indigo-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-indigo-700">{{ $number->official_number }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-2">
                                <div class="text-sm font-bold text-gray-900">{{ $number->owner_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ \Illuminate\Support\Str::limit($number->street_name . ' #' . $number->ext_number . ', ' . $number->suburb, 35) }}
                                </div>
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $number->property_type == 'vivienda' ? 'Casa Habitación' : 'Comercial' }}
                            </td>

                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($number->assignment_date)->format('d/m/Y') }}</div>
                                @if($number->treasury_office_number)
                                <div class="text-xs text-gray-500">Oficio: {{ $number->treasury_office_number }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-right text-sm font-medium">
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
                            <td colspan="5" class="px-4 py-12 text-center">
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
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex justify-end">
                {{ $numbers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('suburbChart').getContext('2d');
            const suburbChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Registros por Colonia',
                        data: @json($chartData),
                        backgroundColor: [
                            '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                            '#3B82F6', '#8B5CF6', '#EC4899', '#F97316', '#14B8A6'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Top 10 Colonias con más registros'
                        }
                    }
                }
            });
        });
    </script>
@endsection

