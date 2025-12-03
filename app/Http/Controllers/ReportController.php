<?php

namespace App\Http\Controllers;

use App\Models\OfficialNumber;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficialNumber::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('colonia')) {
            $query->where('suburb', 'like', '%' . $request->colonia . '%');
        }

        $numbers = $query->latest()->paginate(10)->appends($request->query());

        $suburbStats = OfficialNumber::query()
            ->select('suburb', DB::raw('count(*) as total'))
            ->groupBy('suburb')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $chartLabels = $suburbStats->pluck('suburb');
        $chartData = $suburbStats->pluck('total');

        return view('reports.index', compact('numbers', 'chartLabels', 'chartData'));
    }

    public function export(Request $request)
    {
        $query = OfficialNumber::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('colonia')) {
            $query->where('suburb', 'like', '%' . $request->colonia . '%');
        }

        $numbers = $query->latest()->get();
        $totalRecords = $numbers->count();

        $pdf = Pdf::loadView('reports.report_pdf', compact('numbers', 'totalRecords'));

        return $pdf->download('reporte-numeros-oficiales.pdf');
    }
}

