<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Career;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use PDF;

class ReportController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('permission:ver reportes generales')->only(['index', 'download']);
        $this->middleware('permission:generar reportes peas')->only(['create', 'generate']);
    }
*/
    public function index()
    {
        $reports = Report::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'pagination' => $reports->toArray(),
        ]);
    }

    public function create(string $type)
    {
        $data = [
            'type' => $type,
            'careers' => [],
            'subjects' => [],
            'teachers' => [],
        ];

        // Cargar datos según el tipo de reporte
        switch ($type) {
            case 'subjects':
                $data['careers'] = Career::orderBy('name')->get();
                break;
            case 'evaluations':
            case 'approvals':
                $data['subjects'] = Subject::orderBy('name')->get();
                $data['teachers'] = Teacher::orderBy('name')->get();
                break;
        }

        return Inertia::render('Reports/Create', $data);
    }

    public function generate(Request $request)
    {
        $validated = $this->validateReportRequest($request);

        try {
            // Obtener datos según el tipo de reporte
            $reportData = $this->getReportData($validated);

            // Generar PDF
            $pdf = $this->generatePDF($validated['type'], $reportData);

            // Guardar el archivo
            $fileName = $this->saveReportFile($pdf, $validated['type']);

            // Crear registro en la base de datos
            $report = Report::create([
                'type' => $validated['type'],
                'file_path' => $fileName,
                'filters' => json_encode($validated),
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('reports.index')->with('success', 'Reporte generado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el reporte');
        }
    }

    public function download(Report $report)
    {
        if (!Storage::exists($report->file_path)) {
            return back()->with('error', 'Archivo no encontrado');
        }

        return Storage::download($report->file_path);
    }

    private function validateReportRequest(Request $request)
    {
        $rules = [
            'type' => 'required|in:subjects,evaluations,approvals',
        ];

        switch ($request->type) {
            case 'subjects':
                $rules['career_id'] = 'required|exists:careers,id';
                break;
            case 'evaluations':
                $rules['subject_id'] = 'required|exists:subjects,id';
                $rules['date_from'] = 'required|date';
                $rules['date_to'] = 'required|date|after_or_equal:date_from';
                break;
            case 'approvals':
                $rules['filter_type'] = 'required|in:subject,teacher';
                $rules['period'] = 'required|in:current,last,custom';

                if ($request->filter_type === 'subject') {
                    $rules['subject_id'] = 'required|exists:subjects,id';
                } else {
                    $rules['teacher_id'] = 'required|exists:teachers,id';
                }
                break;
        }

        return $request->validate($rules);
    }

    private function getReportData(array $filters)
    {
        switch ($filters['type']) {
            case 'subjects':
                return $this->getSubjectsReport($filters['career_id']);
            case 'evaluations':
                return $this->getEvaluationsReport($filters);
            case 'approvals':
                return $this->getApprovalsReport($filters);
            default:
                throw new \Exception('Tipo de reporte no válido');
        }
    }

    private function getSubjectsReport($careerId)
    {
        return Subject::where('career_id', $careerId)
            ->with(['career', 'teacher'])
            ->get();
    }

    private function getEvaluationsReport($filters)
    {
        return DB::table('evaluations')
            ->where('subject_id', $filters['subject_id'])
            ->whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
            ->get();
    }

    private function getApprovalsReport($filters)
    {
        $query = DB::table('evaluations')->select('subjects.name as subject_name', DB::raw('COUNT(*) as total_students'), DB::raw('COUNT(CASE WHEN evaluations.first_partial >= 7 AND evaluations.second_partial >= 7 THEN 1 END) as approved_students'), DB::raw('ROUND(COUNT(CASE WHEN evaluations.first_partial >= 7 AND evaluations.second_partial >= 7 THEN 1 END) * 100.0 / COUNT(*), 2) as approval_rate'))->join('subjects', 'evaluations.subject_id', '=', 'subjects.id');

        if ($filters['filter_type'] === 'subject') {
            $query->where('subject_id', $filters['subject_id']);
        } else {
            $query->where('subjects.teacher_id', $filters['teacher_id']);
        }

        return $query->groupBy('subjects.name')->get();
    }

    private function generatePDF($type, $data)
    {
        $view = 'reports.' . $type;
        return PDF::loadView($view, compact('data'));
    }

    private function saveReportFile($pdf, $type)
    {
        $fileName = 'reports/' . $type . '_' . time() . '.pdf';
        Storage::put($fileName, $pdf->output());
        return $fileName;
    }
}
