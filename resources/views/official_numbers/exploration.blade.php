@extends('layouts.panel')

@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <style>
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: all 0.2s ease-out; }
        .modal-exit { opacity: 1; transform: scale(1); }
        .modal-exit-active { opacity: 0; transform: scale(0.95); transition: all 0.2s ease-in; }
    </style>

    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-extrabold text-blue-900">Oficio de Número Oficial Interactivo</h2>
            <p class="text-gray-500 mt-1">Folio: <strong>{{ $officialNumber->official_number }}</strong></p>
        </div>
        <a href="{{ route('official_numbers.list') }}" class="text-sm text-gray-500 hover:text-indigo-600 underline">Volver al Listado</a>
    </div>

    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-600 rounded-xl p-6 text-white shadow-lg">
                <p class="text-blue-100 text-xs font-bold uppercase mb-1">Número Oficial</p>
                <p class="text-4xl font-extrabold">#{{ $officialNumber->ext_number }}</p>
            </div>
            <div class="bg-gray-100 rounded-xl p-6 border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase mb-2">Oficio Tesorería</p>
                <p class="text-2xl font-bold text-gray-800">{{ $officialNumber->treasury_office_number ?? 'S/N' }}</p>
            </div>
            <div class="bg-gray-100 rounded-xl p-6 border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Propietario</p>
                <p class="text-xl font-bold text-gray-900 truncate">{{ $officialNumber->owner_name }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Asignado el {{ \Carbon\Carbon::parse($officialNumber->assignment_date)->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500 mb-6">
                    <p class="text-sm font-bold text-gray-800 uppercase mb-1">Ubicación Registrada:</p>
                    <p class="text-gray-600">
                        Calle {{ $officialNumber->street_name }} #{{ $officialNumber->ext_number }}, Colonia {{ $officialNumber->suburb }}, Municipio {{ $officialNumber->city }}.
                    </p>
                </div>
                <h4 class="font-bold text-blue-600 mb-4">Proporciones del Predio</h4>
                <p class="text-gray-500 text-sm mb-4">Área Total: <strong class="text-gray-900">{{ number_format($officialNumber->area_sqm, 2) }} m²</strong></p>
                <div class="relative h-64 w-full"><canvas id="predioChart"></canvas></div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 flex flex-col items-center justify-center border border-gray-100">
                <h4 class="font-bold text-blue-600 mb-4 self-start">Croquis</h4>
                @if($officialNumber->croquis_base64)
                    <img src="{{ $officialNumber->croquis_base64 }}" class="max-h-64 rounded shadow-sm border border-gray-300">
                @else
                    <div class="h-48 w-full flex items-center justify-center text-gray-400">Sin croquis disponible</div>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Expediente Digital</h3>
                <p class="text-gray-500 text-sm">Cargue los documentos requeridos (Escrituras, INE, Predial, etc).</p>
            </div>
            <a href="{{ route('official_numbers.pdf', $officialNumber->id) }}" target="_blank" class="flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                Descargar PDF Oficial
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-3" id="documents-container"></div>
            
            <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h4 class="font-bold text-gray-600 mb-6 text-xs uppercase tracking-wider text-center">Progreso</h4>
                <div class="relative h-48 w-48">
                    <canvas id="docsChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                        <span class="text-3xl font-extrabold text-gray-800" id="docsPercentText">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeUploadModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-doc-title">Subir Documento</h3>
                        <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="flex space-x-2 mb-6 bg-gray-100 p-1 rounded-lg">
                        <button onclick="toggleMethod('pc')" id="tab-pc" class="flex-1 py-2 text-sm font-bold rounded-md bg-white shadow-sm text-indigo-600 transition">Computadora</button>
                        <button onclick="toggleMethod('qr')" id="tab-qr" class="flex-1 py-2 text-sm font-bold rounded-md text-gray-500 hover:text-indigo-600 transition">Celular (QR)</button>
                    </div>

                    <div id="view-pc">
                        <p class="text-xs text-gray-500 mb-2 italic" id="pc-ine-note" style="display:none;">Nota: Si es INE, suba un solo archivo con ambos lados.</p>
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="doc_type" id="modal-doc-type">
                            <div onclick="document.getElementById('file-upload').click()" class="flex flex-col items-center justify-center w-full h-40 border-2 border-indigo-300 border-dashed rounded-xl cursor-pointer bg-indigo-50 hover:bg-indigo-100 transition relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropzone-content">
                                    <p class="text-sm text-indigo-700 font-bold">Clic para seleccionar archivo</p>
                                </div>
                                <div id="file-feedback" class="hidden flex-col items-center justify-center absolute inset-0 bg-white bg-opacity-90 rounded-xl pointer-events-none">
                                    <p class="text-sm font-bold text-gray-800" id="selected-filename">archivo.pdf</p>
                                </div>
                                <input id="file-upload" name="document_file" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" id="btn-upload" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold disabled:opacity-50" disabled>Guardar Documento</button>
                            </div>
                        </form>
                    </div>
                    
                    <div id="view-qr" class="hidden text-center py-4">
                        <div id="qr-standard-section">
                            <div id="qrcode" class="flex justify-center mb-4 bg-white p-2 inline-block border rounded-lg shadow-sm"></div>
                        </div>
                        <div id="qr-ine-section" class="hidden">
                            <div class="grid grid-cols-2 gap-4">
                                <div><div id="qrcode-front"></div></div>
                                <div><div id="qrcode-back"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
        // --- 1. CONFIGURACIÓN DE GRÁFICA DE PREDIO ---
        const ctx = document.getElementById('predioChart').getContext('2d');
        const frontVal = Number("{{ $officialNumber->front_measurement ?? 0 }}");
        const depthVal = Number("{{ $officialNumber->depth_measurement ?? 0 }}");

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Frente', 'Fondo'],
                datasets: [{
                    label: 'Dimensiones (metros)',
                    data: [frontVal, depthVal],
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        // --- 2. GESTIÓN DE DOCUMENTOS ---
        // Mapeamos las URLs de los documentos ya subidos usando Blade
        const docUrls = {
            1: "{{ $officialNumber->doc_escrituras ? asset('storage/'.$officialNumber->doc_escrituras) : '' }}",
            2: "{{ $officialNumber->doc_constancia ? asset('storage/'.$officialNumber->doc_constancia) : '' }}",
            3: "{{ $officialNumber->doc_ine ? asset('storage/'.$officialNumber->doc_ine) : '' }}",
            4: "{{ $officialNumber->doc_predial ? asset('storage/'.$officialNumber->doc_predial) : '' }}",
            '3_back': "{{ $officialNumber->doc_ine_reverso ? asset('storage/'.$officialNumber->doc_ine_reverso) : '' }}"
        };

        // Mapeo de IDs numéricos a nombres de columna en BD
        const docTypeMap = { 1: 'doc_escrituras', 2: 'doc_constancia', 3: 'doc_ine', 4: 'doc_predial' };
        
        const documents = [
            { id: 1, name: "Escritura del Predio o Título" },
            { id: 2, name: "Constancia Comisariado Ejidal" },
            { id: 3, name: "Copia de INE (Ambos Lados)" },
            { id: 4, name: "Recibo de Predial (Ambos Lados)" }
        ];

        let currentDocId = null;
        let docsChartInstance = null;

        // Función para dibujar la lista de documentos
        function renderDocuments() {
            const container = document.getElementById('documents-container');
            container.innerHTML = '';
            let completedCount = 0;

            documents.forEach(doc => {
                // Verificamos si la URL existe y no está vacía
                const isUploaded = docUrls[doc.id] && docUrls[doc.id].length > 10; // >10 para evitar urls vacías cortas
                if (isUploaded) completedCount++;
                
                const statusColor = isUploaded ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200';
                const iconColor = isUploaded ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400';
                
                let btns = '';
                if (isUploaded) {
                    btns += `<a href="${docUrls[doc.id]}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 shadow-sm transition">Ver</a>`;
                    // Botón extra si es INE reverso
                    if (doc.id === 3 && docUrls['3_back'] && docUrls['3_back'].length > 10) {
                        btns += `<a href="${docUrls['3_back']}" target="_blank" class="ml-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 shadow-sm transition">Reverso</a>`;
                    }
                } else {
                    btns = `<button onclick="openUploadModal(${doc.id})" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md transition">Subir</button>`;
                }

                const html = `
                <div class="flex items-center justify-between p-4 border rounded-xl transition-all duration-200 ${statusColor} hover:shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="${iconColor} p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${isUploaded ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'}
                            </svg>
                        </div>
                        <div><h4 class="font-bold text-gray-800 text-sm">${doc.name}</h4></div>
                    </div>
                    <div class="flex">${btns}</div>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });
            updateChart(completedCount);
        }

        // --- 3. FUNCIONES DEL MODAL ---
        function openUploadModal(id) {
            currentDocId = id;
            const doc = documents.find(d => d.id === id);
            
            // Resetear Modal
            document.getElementById('modal-doc-title').innerText = 'Subir: ' + doc.name;
            document.getElementById('modal-doc-type').value = docTypeMap[id];
            document.getElementById('file-upload').value = "";
            document.getElementById('dropzone-content').classList.remove('hidden');
            document.getElementById('file-feedback').classList.add('hidden');
            document.getElementById('btn-upload').disabled = true;
            document.getElementById('selected-filename').innerText = "";
            
            // Configuración Específica para INE (QR Doble) vs Normal
            if(id === 3) {
                document.getElementById('pc-ine-note').style.display = 'block';
                document.getElementById('qr-standard-section').classList.add('hidden');
                document.getElementById('qr-ine-section').classList.remove('hidden');
                
                // Generar QRs para INE
                document.getElementById('qrcode-front').innerHTML = ""; 
                new QRCode(document.getElementById('qrcode-front'), { text: "INE-FRONT-UPLOAD-" + "{{ $officialNumber->id }}", width: 90, height: 90 });
                
                document.getElementById('qrcode-back').innerHTML = ""; 
                new QRCode(document.getElementById('qrcode-back'), { text: "INE-BACK-UPLOAD-" + "{{ $officialNumber->id }}", width: 90, height: 90 });
            } else {
                document.getElementById('pc-ine-note').style.display = 'none';
                document.getElementById('qr-ine-section').classList.add('hidden');
                document.getElementById('qr-standard-section').classList.remove('hidden');
                
                // Generar QR Estándar
                document.getElementById('qrcode').innerHTML = ""; 
                new QRCode(document.getElementById('qrcode'), { text: "DOC-UPLOAD-" + id + "-" + "{{ $officialNumber->id }}", width: 150, height: 150 });
            }
            
            // Abrir en pestaña PC por defecto
            toggleMethod('pc'); 
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() { 
            document.getElementById('uploadModal').classList.add('hidden'); 
        }

        function toggleMethod(method) {
            const pcView = document.getElementById('view-pc');
            const qrView = document.getElementById('view-qr');
            const tabPc = document.getElementById('tab-pc');
            const tabQr = document.getElementById('tab-qr');
            
            if (method === 'pc') {
                pcView.classList.remove('hidden'); qrView.classList.add('hidden');
                tabPc.classList.add('bg-white', 'text-indigo-600', 'shadow-sm'); tabPc.classList.remove('text-gray-500');
                tabQr.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm'); tabQr.classList.add('text-gray-500');
            } else {
                pcView.classList.add('hidden'); qrView.classList.remove('hidden');
                tabQr.classList.add('bg-white', 'text-indigo-600', 'shadow-sm'); tabQr.classList.remove('text-gray-500');
                tabPc.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm'); tabPc.classList.add('text-gray-500');
            }
        }

        function updateFileName(input) {
            if(input.files && input.files.length > 0) {
                document.getElementById('selected-filename').innerText = input.files[0].name;
                document.getElementById('dropzone-content').classList.add('hidden');
                document.getElementById('file-feedback').classList.remove('hidden');
                document.getElementById('file-feedback').style.display = 'flex';
                document.getElementById('btn-upload').disabled = false;
            }
        }

        // --- 4. ENVÍO DEL FORMULARIO (AJAX) ---
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('btn-upload');
            
            // Estado de carga
            btn.disabled = true; 
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Subiendo...';

            fetch("{{ route('official_numbers.upload_doc', $officialNumber->id) }}", {
                method: 'POST', 
                body: formData,
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Actualizar URL localmente para que se refleje sin recargar
                    const id = Object.keys(docTypeMap).find(k => docTypeMap[k] === document.getElementById('modal-doc-type').value);
                    if(id) docUrls[id] = data.url;
                    
                    renderDocuments(); 
                    closeUploadModal();
                    alert('Documento guardado correctamente.');
                } else { 
                    alert('Error al subir: ' + data.message); 
                }
            })
            .catch(e => { 
                console.error(e);
                alert('Error de conexión con el servidor.'); 
            })
            .finally(() => { 
                btn.disabled = false; 
                btn.innerHTML = 'Guardar Documento'; 
            });
        });

        // --- 5. GRÁFICA DE PROGRESO (Doughnut) ---
        function updateChart(completed) {
            const percent = Math.round((completed / 4) * 100);
            document.getElementById('docsPercentText').innerText = percent + '%';
            
            if(!docsChartInstance) {
                const ctx = document.getElementById('docsChart').getContext('2d');
                docsChartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Listo', 'Pendiente'],
                        datasets: [{ 
                            data: [completed, 4 - completed], 
                            backgroundColor: ['#16a34a', '#e5e7eb'], 
                            borderWidth: 0 
                        }]
                    },
                    options: { 
                        responsive: true, 
                        cutout: '75%', 
                        plugins: { legend: { display: false }, tooltip: { enabled: false } } 
                    }
                });
            } else {
                docsChartInstance.data.datasets[0].data = [completed, 4 - completed];
                docsChartInstance.update();
            }
        }

        // Inicializar vista
        renderDocuments();
    </script>

@endsection