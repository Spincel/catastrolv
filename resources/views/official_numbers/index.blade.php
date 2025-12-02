@extends('layouts.panel')

@section('content')

    <style>
        /* Contenedores y Diseño */
        .top-section-container { @apply bg-[#e5e7eb] rounded-3xl p-8 mb-8 border border-gray-300 shadow-inner relative overflow-hidden; }
        .green-card-floating { @apply bg-[#d1fae5] rounded-xl p-1 shadow-sm border border-green-200; }
        .green-card-inner { @apply bg-[#d1fae5] rounded-lg px-4 py-3 relative; }
        
        /* Inputs personalizados */
        .line-input { @apply w-full bg-transparent border-b-2 border-gray-300 text-gray-800 text-lg font-medium px-1 py-1 focus:outline-none focus:border-indigo-500 transition-colors placeholder-gray-400; }
        .box-label { @apply block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1; }
        .box-input { @apply w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-2.5 transition-all; }
        
        /* Contenedor del Mapa */
        #map-container { 
            height: 500px; 
            width: 100%; 
            border-radius: 0.75rem; 
            overflow: hidden; 
            border: 4px solid white; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            position: relative;
        }
    </style>

    <header class="mb-8">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Oficio de Número Oficial Interactivo</h2>
        <p class="text-gray-500 mt-1">Folio: <span class="text-indigo-600 font-bold">MXL-{{ date('Y') }}-XXXX</span> (Se generará al guardar)</p>
    </header>

    @if (session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
            {{ session('success') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="officialNumberForm" action="{{ route('official_numbers.store') }}" method="POST">
        @csrf
        <input type="hidden" id="croquis_base64" name="croquis_base64" value="">

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm relative overflow-hidden mb-8">
            <div class="absolute top-0 left-0 w-1 bg-amber-600 h-full"></div>
            <div class="flex items-center mb-5">
                <span class="bg-amber-100 text-amber-500 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3">1</span>
                <h3 class="text-lg font-bold text-gray-800">Trámite Nuevo</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <label class="box-label">Solicitante / Propietario</label>
                    <input type="text" id="owner_name" name="owner_name" class="w-full bg-transparent border-b border-gray-300 text-base font-medium text-gray-800 focus:outline-none placeholder-gray-400" placeholder="Nombre Completo" required>
                </div>
                
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 relative">
                    <label class="box-label">Ficha Pago Tesorería</label>
                    <input type="text" id="treasury_office_number" name="treasury_office_number" class="w-full bg-transparent border-b border-gray-300 text-base font-medium text-gray-800 focus:outline-none placeholder-gray-400" placeholder="Ej. 12345" required>
                    <div class="absolute right-2 top-3 bg-white/50 rounded px-2 py-1 border border-gray-300">
                        <label class="block text-[8px] font-bold text-gray-500 uppercase">FECHA PAGO</label>
                        <input type="date" name="treasury_date" class="bg-transparent text-[10px] font-bold text-indigo-700 focus:outline-none uppercase" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="green-card-floating h-full">
                        <div class="green-card-inner h-full flex flex-col justify-center">
                            <label class="block text-center text-xs font-bold text-green-800 mb-1 uppercase tracking-wide">Uso del Predio</label>
                            <select name="property_type" class="w-full bg-transparent border-b border-green-300 text-base font-bold text-green-900 focus:outline-none text-center cursor-pointer">
                                <option value="vivienda">Vivienda 🏠</option>
                                <option value="comercial">Comercial 🏢</option>
                            </select>
                        </div> 
                    </div>                                    
                </div>
            </div>  
        </div>        

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            <div class="xl:col-span-5 space-y-6">
                
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 bg-indigo-500 h-full"></div>
                    <div class="flex items-center mb-5">
                        <span class="bg-indigo-100 text-indigo-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold mr-3">2</span>
                        <h3 class="text-lg font-bold text-gray-800">Ubicación Geográfica</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="box-label">Calle Principal</label>
                            <input type="text" id="street_name" name="street_name" class="w-full bg-transparent border-b border-gray-300 text-lg font-bold text-gray-800 focus:outline-none pb-1 placeholder-gray-400" placeholder="Nombre de la calle" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <label class="box-label">Número Exterior</label>
                                <input type="text" id="ext_number" name="ext_number" class="w-full bg-transparent border-b border-gray-300 text-base font-bold text-gray-800 focus:outline-none text-center" placeholder="#" required>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <label class="box-label">Colonia / Barrio</label>
                                <input type="text" id="suburb" name="suburb" class="w-full bg-transparent border-b border-gray-300 text-base font-medium text-gray-800 focus:outline-none" placeholder="Colonia" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-200 border border-gray-300 rounded-lg p-3">
                                <label class="box-label">Municipio</label>
                                <input type="text" id="city" name="city" class="w-full bg-transparent text-gray-500 cursor-not-allowed font-bold text-sm" value="Santiago Ixcuintla" readonly>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <label class="box-label">Interior (Opcional)</label>
                                <input type="text" id="int_number" name="int_number" class="w-full bg-transparent border-b border-gray-300 text-sm text-gray-800 focus:outline-none" placeholder="Int. A">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <label class="text-[10px] font-bold text-red-700 uppercase mb-2 block">
                            <i class="fas fa-tag mr-1"></i> Referencias del Croquis (Laterales)
                        </label>
                        <div class="grid grid-cols-1 gap-2 bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                            <div class="flex gap-2">
                                <input type="text" id="ref_entre_calle1" class="box-input h-8 text-xs bg-white" placeholder="Lateral Izquierda (Entre calle...)">
                                <input type="text" id="ref_y_calle2" class="box-input h-8 text-xs bg-white" placeholder="Lateral Derecha (Y calle...)">
                            </div>
                            <input type="text" id="ref_calle_trasera" class="box-input h-8 text-xs bg-white" placeholder="Calle Trasera (Posterior)">
                        </div>
                        <input type="hidden" name="referencia_cercana" id="referencia_cercana">
                        
                        <input type="hidden" name="colindancia_norte" value="N/A">
                        <input type="hidden" name="colindancia_sur" value="N/A">
                        <input type="hidden" name="colindancia_este" value="N/A">
                        <input type="hidden" name="colindancia_oeste" value="N/A">
                        <input type="hidden" name="curp_rfc" value="">
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 bg-emerald-500 h-full"></div>
                    <div class="flex items-center mb-4">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold mr-3">3</span>
                        <h3 class="text-lg font-bold text-gray-800">Dimensiones del Terreno</h3>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="box-label text-indigo-600">Frente (m)</label>
                            <input type="number" step="0.01" id="front_measurement" name="front_measurement" class="box-input text-xl font-bold text-indigo-900 bg-gray-50" value="10" required oninput="updateChart()">
                        </div>
                        <div>
                            <label class="box-label text-emerald-600">Fondo (m)</label>
                            <input type="number" step="0.01" id="depth_measurement" name="depth_measurement" class="box-input text-xl font-bold text-emerald-900 bg-gray-50" value="20" required oninput="updateChart()">
                        </div>
                    </div>
                    
                    <div class="h-32 w-full mt-2">
                        <canvas id="liveChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-7 space-y-6">
                <div class="bg-white border border-gray-300 rounded-xl p-1 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="flex justify-between items-center px-4 py-3 bg-gray-100 rounded-t-lg border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Croquis Satelital
                        </h3>
                        <span id="geocoding-status" class="text-[10px] font-black bg-gray-200 text-gray-600 px-2 py-1 rounded uppercase">ESPERANDO DATOS</span>
                    </div>
                    
                    <div class="relative bg-gray-200 flex-grow rounded-b-lg overflow-hidden p-2">
                        <div id="map-container">
                            <div id="map" class="w-full h-full"></div>
                        </div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur p-3 rounded-lg border border-gray-300 text-xs text-gray-600 shadow-lg hidden md:block max-w-xs z-10">
                            <p class="font-bold mb-1 text-indigo-600">Pasos:</p>
                            <ol class="list-decimal list-inside space-y-1">
                                <li>Llene ubicación y medidas.</li>
                                <li>El mapa ubicará el predio.</li>
                                <li>Ajuste el recuadro rojo si es necesario.</li>
                                <li>Arrastre las etiquetas de calles.</li>
                                <li>Presione "Capturar Croquis".</li>
                            </ol>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-200 space-y-3">
                        <button type="button" onclick="captureCroquis()" id="capture-button" class="w-full py-3 bg-white border-2 border-indigo-200 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 hover:border-indigo-400 transition flex justify-center items-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed shadow-sm" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Generar y Capturar Croquis
                        </button>

                        <button type="submit" id="submit-button" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 flex items-center justify-center text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Guardar Registro Oficial
                        </button>
                    </div>
                </div>                              
            </div>
        </div>
    </form>

    <script>
        // --- 1. CONFIGURACIÓN INICIAL ---
        let liveChartInstance = null;
        let map, geocoder, marker, predioPolygon = null, centerLocation = null, searchTimeout;
        let isCroquisCaptured = false;
        let labelMarkers = {}; 
        let labelTexts = {};
        
        // Factores de conversión aproximados (para dibujar el polígono en metros sobre el mapa)
        const METERS_TO_LATITUDE = 1 / 111132; 
        const METERS_TO_LONGITUDE_AT_EQUATOR = 1 / 111319;

        // --- 2. GRÁFICA DE BARRAS DE MEDIDAS ---
        function updateChart() {
            const f = parseFloat(document.getElementById('front_measurement').value) || 0;
            const d = parseFloat(document.getElementById('depth_measurement').value) || 0;
            
            // Si ya existe la gráfica, solo actualizamos datos
            if (liveChartInstance) {
                liveChartInstance.data.datasets[0].data = [f, d]; 
                liveChartInstance.update();
            } else {
                // Crear gráfica nueva
                const ctx = document.getElementById('liveChart').getContext('2d');
                liveChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: { 
                        labels: ['Frente', 'Fondo'], 
                        datasets: [{ 
                            label: 'Metros', 
                            data: [f, d], 
                            backgroundColor: ['#6366f1', '#10b981'], 
                            borderRadius: 4, 
                            barThickness: 50 
                        }] 
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { legend: { display: false } }, 
                        scales: { 
                            y: { display: true, beginAtZero: true }, 
                            x: { grid: { display: false } } 
                        } 
                    }
                });
            }
            
            // Disparar redibujado en mapa si ya está cargado
            if(typeof geocodeAndDraw === 'function') {
                invalidateCroquis(); // Si cambian medidas, el croquis viejo ya no sirve
                clearTimeout(searchTimeout); 
                searchTimeout = setTimeout(geocodeAndDraw, 1000); // Esperar 1seg a que termine de escribir
            }
        }

        // --- 3. INICIALIZACIÓN DE GOOGLE MAPS ---
        function initMap() {
            geocoder = new google.maps.Geocoder();
            // Coordenadas por defecto (Centro de Santiago Ixcuintla aprox)
            const defaultLoc = { lat: 21.8082, lng: -105.2052 }; 
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15, 
                center: defaultLoc, 
                mapTypeId: 'hybrid', // Satélite con nombres de calles
                disableDefaultUI: false, 
                mapTypeControl: true, 
                streetViewControl: false,
                tilt: 0
            });
            
            setupAddressListeners();
            setupFormSubmission();
            updateChart(); // Inicializar gráfica
        }

        // --- 4. ESCUCHADORES DE EVENTOS DE INPUTS ---
        function setupAddressListeners() {
            // Campos que afectan la geocodificación
            const fields = ['street_name', 'ext_number', 'suburb', 'city', 'front_measurement', 'depth_measurement'];
            fields.forEach(id => {
                const el = document.getElementById(id);
                if(el) {
                    el.addEventListener('input', () => {
                        invalidateCroquis(); 
                        clearTimeout(searchTimeout); 
                        searchTimeout = setTimeout(geocodeAndDraw, 1500); // Buscar tras 1.5s de inactividad
                        
                        // Si cambia la calle, actualizar etiqueta principal
                        if(id === 'street_name') updateLabel('main_street', el.value);
                    });
                }
            });

            // Campos de etiquetas laterales (Referencias)
            const labels = [
                { id: 'ref_entre_calle1', key: 'ref1' }, 
                { id: 'ref_y_calle2', key: 'ref2' }, 
                { id: 'ref_calle_trasera', key: 'ref3' }
            ];
            
            labels.forEach(item => {
                const el = document.getElementById(item.id);
                if(el) {
                    el.addEventListener('input', () => {
                        updateLabel(item.key, el.value); 
                        updateReferenciaString(); 
                        invalidateCroquis();
                    });
                }
            });
        }

        // --- 5. LÓGICA DE ETIQUETAS EN MAPA ---
        function createLabelIcon(text, isMain=false) {
            const width = (text.length * 8) + 20; 
            const color = isMain ? "#ef4444" : "#4f46e5"; // Rojo para principal, Azul para laterales
            // Crear un SVG dinámico como icono
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="30" viewBox="0 0 ${width} 30">
                <rect x="0" y="0" width="${width}" height="30" rx="4" fill="white" stroke="${color}" stroke-width="2"/>
                <text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-family="sans-serif" font-size="11" font-weight="bold" fill="#000">${text.toUpperCase()}</text>
            </svg>`;
            return { 
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), 
                scaledSize: new google.maps.Size(width, 30), 
                anchor: new google.maps.Point(width/2, 15) 
            };
        }

        function updateLabel(key, text) {
            if(!text) { 
                if(labelMarkers[key]) { labelMarkers[key].setMap(null); delete labelMarkers[key]; } 
                return; 
            }
            
            labelTexts[key] = text;
            if(!map || !centerLocation) return;
            
            const isMain = key === 'main_street';
            const icon = createLabelIcon(text, isMain);
            
            if(labelMarkers[key]) {
                labelMarkers[key].setIcon(icon);
            } else {
                // Posición inicial relativa al centro
                let latOffset = 0, lngOffset = 0;
                if(key === 'ref1') lngOffset = -0.00030; // Izquierda
                if(key === 'ref2') lngOffset = 0.00030;  // Derecha
                if(key === 'ref3') latOffset = 0.00030;  // Arriba
                if(key === 'main_street') latOffset = -0.00030; // Abajo (Frente)

                labelMarkers[key] = new google.maps.Marker({ 
                    position: { lat: centerLocation.lat() + latOffset, lng: centerLocation.lng() + lngOffset }, 
                    map: map, 
                    icon: icon, 
                    draggable: true, // ¡Son arrastrables!
                    zIndex: isMain ? 100 : 50 
                });
                
                // Si mueven la etiqueta, invalidar croquis
                labelMarkers[key].addListener('dragend', invalidateCroquis);
            }
        }

        function updateReferenciaString() {
            const c1 = document.getElementById('ref_entre_calle1').value;
            const c2 = document.getElementById('ref_y_calle2').value;
            const c3 = document.getElementById('ref_calle_trasera').value;
            let parts = []; 
            if(c1) parts.push(c1); 
            if(c2) parts.push(c2); 
            if(c3) parts.push(c3);
            let str = parts.length > 0 ? "Entre " + parts.join(" y ") : "";
            document.getElementById('referencia_cercana').value = str;
        }

        // --- 6. GEOCODIFICACIÓN (Buscar Dirección) ---
        function geocodeAndDraw() {
            const street = document.getElementById('street_name').value;
            const num = document.getElementById('ext_number').value;
            const city = document.getElementById('city').value;
            const suburb = document.getElementById('suburb').value;
            const w = parseFloat(document.getElementById('front_measurement').value);
            const h = parseFloat(document.getElementById('depth_measurement').value);
            
            // Validar que haya datos suficientes
            if(!street || !num || !city || !w || !h) return;
            
            const address = `${street} ${num}, ${suburb}, ${city}, Nayarit, Mexico`;
            
            // Feedback visual
            const statusBadge = document.getElementById('geocoding-status');
            statusBadge.innerText = "BUSCANDO...";
            statusBadge.className = "text-[9px] font-black bg-blue-100 text-blue-700 px-2 py-1 rounded animate-pulse";
            
            geocoder.geocode({ address: address }, (results, status) => {
                if (status === "OK") {
                    centerLocation = results[0].geometry.location;
                    map.setCenter(centerLocation); 
                    map.setZoom(19); // Zoom cercano
                    
                    drawRect(centerLocation, w, h);
                    
                    statusBadge.innerText = "UBICADO";
                    statusBadge.className = "text-[9px] font-black bg-green-100 text-green-700 px-2 py-1 rounded";
                    
                    // Habilitar botón de captura
                    document.getElementById('capture-button').disabled = false;
                    
                    // Actualizar etiquetas en el mapa
                    updateLabel('main_street', street);
                    updateLabel('ref1', document.getElementById('ref_entre_calle1').value);
                    updateLabel('ref2', document.getElementById('ref_y_calle2').value);
                    updateLabel('ref3', document.getElementById('ref_calle_trasera').value);
                } else {
                    statusBadge.innerText = "NO ENCONTRADO";
                    statusBadge.className = "text-[9px] font-black bg-red-100 text-red-700 px-2 py-1 rounded";
                }
            });
        }

        // --- 7. DIBUJAR POLÍGONO DEL PREDIO ---
        function drawRect(center, w, h) {
            if(predioPolygon) predioPolygon.setMap(null);
            
            // Conversión aproximada de metros a grados lat/lng
            const latRad = center.lat() * Math.PI / 180;
            const metersLng = METERS_TO_LONGITUDE_AT_EQUATOR / Math.cos(latRad);
            
            // Coordenadas del rectángulo (centrado en la ubicación)
            const coords = [
                { lat: center.lat() + (h/2)*METERS_TO_LATITUDE, lng: center.lng() - (w/2)*metersLng }, // NO
                { lat: center.lat() + (h/2)*METERS_TO_LATITUDE, lng: center.lng() + (w/2)*metersLng }, // NE
                { lat: center.lat() - (h/2)*METERS_TO_LATITUDE, lng: center.lng() + (w/2)*metersLng }, // SE
                { lat: center.lat() - (h/2)*METERS_TO_LATITUDE, lng: center.lng() - (w/2)*metersLng }  // SO
            ];
            
            // Crear polígono rojo semitransparente
            predioPolygon = new google.maps.Polygon({ 
                paths: coords, 
                strokeColor: '#ef4444', 
                strokeWeight: 2, 
                fillColor: '#ef4444', 
                fillOpacity: 0.35, 
                map: map, 
                draggable: true, // Permitir ajuste manual
                editable: true   // Permitir cambiar forma
            });
            
            // Si el usuario mueve el polígono, actualizar el centro para la captura
            google.maps.event.addListener(predioPolygon, 'dragend', function() {
                const bounds = new google.maps.LatLngBounds();
                predioPolygon.getPath().forEach(function(latLng) { bounds.extend(latLng); });
                centerLocation = bounds.getCenter(); 
                invalidateCroquis();
            });
        }

        function invalidateCroquis() {
            isCroquisCaptured = false;
            const btn = document.getElementById('capture-button');
            btn.disabled = false; 
            btn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Generar y Capturar Croquis';
            btn.className = "w-full py-3 bg-white border-2 border-indigo-200 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition flex justify-center items-center gap-2 text-sm";
        }

// --- 8. CAPTURA DE IMAGEN (CANVAS) CORREGIDA ---
        function captureCroquis() {
            if(!centerLocation) return;
            
            // Crear canvas
            const canvas = document.createElement('canvas'); 
            canvas.width = 600; 
            canvas.height = 600;
            const ctx = canvas.getContext('2d');
            
            // 1. Fondo Blanco
            ctx.fillStyle = "white"; 
            ctx.fillRect(0,0,600,600);
            
            // 2. Dibujar Manzana (Contexto)
            ctx.fillStyle = "#f3f4f6"; 
            ctx.strokeStyle = "#9ca3af"; 
            ctx.lineWidth = 2;
            ctx.fillRect(100, 100, 400, 400); 
            ctx.strokeRect(100, 100, 400, 400);

            // 3. Determinar Orientación del Predio
            // Usamos la calle principal para saber dónde está el "Frente" (Abajo por defecto)
            let mainSide = 'bottom';
            if(labelMarkers['main_street']) {
                const p = labelMarkers['main_street'].getPosition();
                const dy = p.lat() - centerLocation.lat();
                const dx = p.lng() - centerLocation.lng();
                if(Math.abs(dx) > Math.abs(dy)) mainSide = dx > 0 ? 'right' : 'left';
                else mainSide = dy > 0 ? 'top' : 'bottom';
            }

            // 4. Dibujar el Rectángulo del Predio
            const wVal = parseFloat(document.getElementById('front_measurement').value);
            const hVal = parseFloat(document.getElementById('depth_measurement').value);
            // Escala inteligente
            const scale = 150 / Math.max(wVal, hVal); 
            const vw = wVal * scale; 
            const vh = hVal * scale;
            
            ctx.fillStyle = "#60a5fa"; 
            ctx.strokeStyle = "#2563eb"; 
            ctx.lineWidth = 2;
            
            // Calcular posición (x,y) basada en el lado del frente
            let px, py, pw, ph;
            
            // Si el frente es ABAJO (Bottom), el predio se dibuja centrado abajo
            if(mainSide === 'bottom') { px = 300 - vw/2; py = 500 - vh; pw = vw; ph = vh; }
            else if(mainSide === 'top') { px = 300 - vw/2; py = 100; pw = vw; ph = vh; }
            else if(mainSide === 'left') { px = 100; py = 300 - vw/2; pw = vh; ph = vw; } // Frente a la izq -> ancho es vertical
            else if(mainSide === 'right') { px = 500 - vh; py = 300 - vw/2; pw = vh; ph = vw; }
            
            ctx.fillRect(px, py, pw, ph); 
            ctx.strokeRect(px, py, pw, ph);
            
            // 5. Número Exterior
            const extNum = document.getElementById('ext_number').value;
            ctx.fillStyle = "white"; ctx.font = "bold 16px Arial"; ctx.textAlign = "center"; ctx.textBaseline = "middle";
            ctx.fillText("#" + extNum, px + pw/2, py + ph/2);
            
            // 6. Cotas (Medidas)
            ctx.fillStyle = "#1e3a8a"; ctx.font = "bold 12px Arial";
            if(mainSide === 'bottom' || mainSide === 'top') {
                ctx.fillText(wVal + "m", px + pw/2, mainSide==='bottom' ? py+ph+15 : py-5); // Frente
                ctx.fillText(hVal + "m", px-15, py + ph/2); // Fondo (Lateral)
            } else {
                ctx.fillText(wVal + "m", mainSide==='left' ? px-15 : px+pw+15, py + ph/2); 
                ctx.fillText(hVal + "m", px + pw/2, py-5); 
            }

            // 7. DIBUJAR TODAS LAS ETIQUETAS DE CALLES (CORREGIDO)
            const items = [
                { key: 'main_street', val: document.getElementById('street_name').value },
                { key: 'ref1', val: document.getElementById('ref_entre_calle1').value },
                { key: 'ref2', val: document.getElementById('ref_y_calle2').value },
                { key: 'ref3', val: document.getElementById('ref_calle_trasera').value }
            ];

            // Función auxiliar para dibujar texto rotado
            const drawText = (text, side) => {
                ctx.fillStyle = "#111827"; ctx.font = "bold 14px Arial";
                if(side === 'bottom') { 
                    ctx.textAlign = "center"; ctx.textBaseline = "top"; 
                    ctx.fillText(text, 300, 530); 
                }
                if(side === 'top') { 
                    ctx.textAlign = "center"; ctx.textBaseline = "bottom"; 
                    ctx.fillText(text, 300, 70); 
                }
                if(side === 'left') { 
                    ctx.save(); ctx.translate(70, 300); ctx.rotate(-Math.PI/2); 
                    ctx.textAlign = "center"; ctx.textBaseline = "bottom"; 
                    ctx.fillText(text, 0, 0); ctx.restore(); 
                }
                if(side === 'right') { 
                    ctx.save(); ctx.translate(530, 300); ctx.rotate(Math.PI/2); 
                    ctx.textAlign = "center"; ctx.textBaseline = "bottom"; 
                    ctx.fillText(text, 0, 0); ctx.restore(); 
                }
            };

            items.forEach(item => {
                // Solo si el usuario escribió algo
                if(item.val) {
                    let sideToDraw = '';

                    // A. Si la etiqueta está EN EL MAPA, usamos su posición exacta
                    if(labelMarkers[item.key] && labelMarkers[item.key].getMap()) {
                        const p = labelMarkers[item.key].getPosition();
                        const dy = p.lat() - centerLocation.lat();
                        const dx = p.lng() - centerLocation.lng();
                        
                        // Determinar cuadrante (Arriba/Abajo/Izq/Der)
                        if(Math.abs(dx) > Math.abs(dy)) {
                            sideToDraw = dx > 0 ? 'right' : 'left';
                        } else {
                            sideToDraw = dy > 0 ? 'top' : 'bottom';
                        }
                    } 
                    // B. Si NO movió la etiqueta en el mapa, usamos posiciones por defecto
                    else {
                        if(item.key === 'main_street') sideToDraw = 'bottom';
                        if(item.key === 'ref1') sideToDraw = 'left';
                        if(item.key === 'ref2') sideToDraw = 'right';
                        if(item.key === 'ref3') sideToDraw = 'top';
                    }

                    drawText(item.val.toUpperCase(), sideToDraw);
                }
            });

            // 8. Norte y Footer
            const nx = 550, ny = 50;
            ctx.strokeStyle = "black"; ctx.beginPath(); ctx.moveTo(nx, ny+15); ctx.lineTo(nx, ny-15); ctx.lineTo(nx-5, ny-5); ctx.moveTo(nx, ny-15); ctx.lineTo(nx+5, ny-5); ctx.stroke();
            ctx.fillStyle = "black"; ctx.font = "10px Arial"; ctx.textAlign = "center"; ctx.fillText("N", nx, ny-25);
            ctx.fillStyle = "#6b7280"; ctx.font = "italic 10px Arial";
            ctx.fillText("CROQUIS GENERADO DIGITALMENTE - NO A ESCALA", 300, 580);

            // 9. Guardar
            document.getElementById('croquis_base64').value = canvas.toDataURL();
            isCroquisCaptured = true;
            
            const btn = document.getElementById('capture-button');
            btn.innerHTML = "✅ Croquis Capturado y Listo";
            btn.className = "w-full border-2 border-green-600 bg-green-100 text-green-700 font-bold py-3 px-4 rounded-xl shadow-sm";
        }

        function setupFormSubmission() {
            document.getElementById('officialNumberForm').onsubmit = (e) => {
                if(!isCroquisCaptured) { 
                    e.preventDefault(); 
                    alert("⚠️ Atención: Debes presionar el botón 'Generar y Capturar Croquis' antes de guardar."); 
                }
            };
        }
    </script>
    
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHgvIPEtRIsrdeTojSVGYg2-f1aR2cPKI&libraries=places,geometry&callback=initMap"></script>

@endsection