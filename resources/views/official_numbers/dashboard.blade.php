@extends('layouts.panel')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Resumen Ejecutivo</h1>
            <div class="text-sm text-gray-500">Año en curso: <strong>{{ date('Y') }}</strong></div>
        </div>
    </div>               

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <p class="text-gray-500 text-xs font-bold uppercase">Total Expedientes</p>
            <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ number_format($totalRecords) }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <p class="text-indigo-500 text-xs font-bold uppercase">Registros este Mes</p>
            <p class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $totalThisMonth }}</p>
        </div>
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white">
            <p class="text-indigo-100 text-xs font-bold uppercase">Acceso Rápido</p>
            <a href="{{ route('official_numbers.index') }}" class="mt-3 inline-block bg-white text-indigo-600 text-sm font-bold py-2 px-4 rounded-lg hover:bg-gray-100 transition">
                + Crear Nuevo
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Actividad Mensual</h3>
        <div class="relative w-full h-80">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h3 class="font-bold text-gray-700">Últimos Registros</h3>
            
            <form method="GET" action="{{ route('dashboard') }}" class="relative w-full sm:w-96">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Buscar por folio, nombre o calle..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Folio</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Propietario</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($numbers as $number)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-700">
                            {{ $number->official_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $number->owner_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Illuminate\Support\Str::limit($number->street_name, 20) }} #{{ $number->ext_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($number->assignment_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex justify-end items-center space-x-3">
                                <a href="{{ route('official_numbers.explore', $number->id) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Ver Detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('official_numbers.pdf', $number->id) }}" target="_blank" class="text-gray-400 hover:text-red-600 transition" title="Descargar PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($numbers->isEmpty())
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No se encontraron registros.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($numbers->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $numbers->appends(['search' => request('search')])->links() }} 
        </div>
        @endif
    </div>

    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const labels = @json($monthNames);
        const data = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros por Mes',
                    data: data,
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgb(79, 70, 229)',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endsection