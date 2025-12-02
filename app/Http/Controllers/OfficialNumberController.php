<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficialNumber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF; 

class OfficialNumberController extends Controller
{
    public function index()
    {
        $numbers = OfficialNumber::orderBy('assignment_date', 'desc')->get();
        $recentNumbers = OfficialNumber::orderBy('assignment_date', 'desc')->take(5)->get();
        return view('official_numbers.index', compact('numbers', 'recentNumbers'));
    }
    
    public function list(Request $request)
    {
        // 1. Iniciamos la consulta base
        $query = OfficialNumber::orderBy('assignment_date', 'desc');

        // 2. Filtro de Texto (Buscador)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('official_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('street_name', 'like', "%{$search}%");
            });
        }

        // 3. NUEVO: Filtro de Fechas (Desde - Hasta)
        if ($request->filled('date_from')) {
            $query->whereDate('assignment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('assignment_date', '<=', $request->date_to);
        }

        // 4. Paginación
        $numbers = $query->paginate(20);

        return view('official_numbers.list', compact('numbers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_name' => 'required|string|max:255',
            'property_type' => 'required|string|in:vivienda,comercial',
            'street_name' => 'required|string|max:100',
            'ext_number' => 'required|string|max:20',
            'suburb' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'front_measurement' => 'required|numeric|min:0.01',
            'depth_measurement' => 'required|numeric|min:0.01',
            'treasury_office_number' => 'required|string|max:50',
            'treasury_date' => 'required|date', // VALIDAR FECHA
            // Opcionales
            'colindancia_norte' => 'nullable|string',
            'colindancia_sur' => 'nullable|string',
            'colindancia_este' => 'nullable|string',
            'colindancia_oeste' => 'nullable|string',
            'referencia_cercana' => 'nullable|string',
            'curp_rfc' => 'nullable|string',
            'int_number' => 'nullable|string',
            'croquis_base64' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $front = $request->input('front_measurement');
        $depth = $request->input('depth_measurement');
        $area_sqm = $front * $depth;
        $folioTexto = OfficialNumber::generateOfficialNumber(); 
        
        // Usamos la fecha del formulario, no la actual
        $assignmentDate = Carbon::parse($request->input('treasury_date')); 

        $nuevoRegistro = OfficialNumber::create([
            'official_number' => $folioTexto,
            'assignment_date' => $assignmentDate, // Guardamos la fecha elegida como fecha oficial
            'treasury_date' => $request->input('treasury_date'), // Y también en su campo específico
            'treasury_office_number' => $request->input('treasury_office_number'),
            'property_type' => $request->input('property_type'),
            'owner_name' => $request->input('owner_name'),
            'curp_rfc' => $request->input('curp_rfc'),
            'street_name' => $request->input('street_name'),
            'ext_number' => $request->input('ext_number'),
            'int_number' => $request->input('int_number'),
            'suburb' => $request->input('suburb'),
            'city' => $request->input('city'),
            'colindancia_norte' => $request->input('colindancia_norte'),
            'colindancia_sur' => $request->input('colindancia_sur'),
            'colindancia_este' => $request->input('colindancia_este'),
            'colindancia_oeste' => $request->input('colindancia_oeste'),
            'referencia_cercana' => $request->input('referencia_cercana'),
            'front_measurement' => $front,
            'depth_measurement' => $depth,
            'area_sqm' => $area_sqm,
            'croquis_base64' => $request->input('croquis_base64'),
            'assigned_by_user_id' => auth()->id() ?? null,
        ]);

        return redirect()->route('official_numbers.explore', $nuevoRegistro->id)
                         ->with('success', 'Número Oficial asignado correctamente: ' . $folioTexto);
    }
    
    // ... (Resto de funciones explore, pdf, upload igual) ...
    // Asegúrate de copiar las demás funciones del controlador anterior aquí
    public function explore(OfficialNumber $officialNumber)
    {
        return view('official_numbers.exploration', compact('officialNumber'));
    }

    public function generatePdf(OfficialNumber $officialNumber)
    {
        $data = [
            'title' => 'Asignación de Número Oficial',
            'date' => Carbon::parse($officialNumber->treasury_date)->format('d/m/Y'), // Usar fecha de tesorería en el PDF
            'officialNumber' => $officialNumber,
        ];

        $pdf = PDF::loadView('official_numbers.official_number_pdf', $data)
                  ->setPaper('letter', 'portrait');

        return $pdf->download("Numero_Oficial_{$officialNumber->official_number}.pdf");
    }

    public function uploadDocument(Request $request, OfficialNumber $officialNumber)
    {
        // ... (Misma lógica de subida que ya tenías) ...
        $validator = Validator::make($request->all(), [
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'doc_type' => 'required|string|in:doc_escrituras,doc_constancia,doc_ine,doc_ine_reverso,doc_predial',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $file = $request->file('document_file');
        $docType = $request->input('doc_type');
        
        $filename = $docType . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("documents/{$officialNumber->id}", $filename, 'public');

        $officialNumber->$docType = $path;
        $officialNumber->save();

        return response()->json([
            'success' => true,
            'message' => 'Documento guardado correctamente',
            'url' => asset('storage/' . $path)
        ]);
    }

    public function dashboard(Request $request) // <--- Agregamos (Request $request)
    {
        // 1. Obtener conteo por mes (Estadísticas)
        $monthlyStats = OfficialNumber::selectRaw('MONTH(assignment_date) as month, COUNT(*) as count')
            ->whereYear('assignment_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // 2. Preparar datos de Gráfica con Meses en Español
        $chartData = [];
        $monthNames = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyStats[$i] ?? 0;
        }

        // 3. Totales generales
        $totalRecords = OfficialNumber::count();
        $totalThisMonth = OfficialNumber::whereMonth('assignment_date', date('m'))
                                        ->whereYear('assignment_date', date('Y'))
                                        ->count();

        // 4. Listado con Lógica de Búsqueda (NUEVO)
        $query = OfficialNumber::orderBy('assignment_date', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('official_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('street_name', 'like', "%{$search}%");
            });
        }

        $numbers = $query->paginate(20);

        return view('official_numbers.dashboard', compact('chartData', 'monthNames', 'totalRecords', 'totalThisMonth', 'numbers'));
    }
}