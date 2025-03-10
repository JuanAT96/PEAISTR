<?php

namespace App\Http\Controllers;

use App\Models\SpecificCompetence;
use Illuminate\Http\Request;
use App\Models\Subject;
use Inertia\Inertia;
use App\Http\Requests\SpecificCompetenceRequest;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class SpecificCompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $specificcompetencesQuery = SpecificCompetence::with(['subject']);

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                // Obtener career_ids relacionados con las materias del docente
                $careerIds = CareerSubject::whereIn('subject_id', $subjectTeacherIds)->pluck('career_id')->toArray();

                if (!empty($careerIds)) {
                    // Obtener subject_ids que pertenecen a las carreras del docente
                    $finalSubjectIds = CareerSubject::whereIn('career_id', $careerIds)->whereIn('subject_id', $subjectTeacherIds)->pluck('subject_id')->toArray();

                    if (!empty($finalSubjectIds)) {
                        $specificcompetencesQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $specificcompetencesQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $specificcompetencesQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $specificcompetencesQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $specificcompetences = $specificcompetencesQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$specificcompetences = SpecificCompetence::with('subject')
            ->orderBy('id', 'asc')
            ->paginate(NUMERO_ELEMENTOS_POR_PAGINA);*/
        return inertia('SpecificCompetences/Index', [
            'specificcompetences' => $specificcompetences->items(),
            'pagination' => [
                'total' => $specificcompetences->total(),
                'per_page' => $specificcompetences->perPage(),
                'current_page' => $specificcompetences->currentPage(),
                'last_page' => $specificcompetences->lastPage(),
                'from' => $specificcompetences->firstItem(),
                'to' => $specificcompetences->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjectsQuery = Subject::select('id', 'subject_name', 'subject_code');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                $careerIds = CareerSubject::whereIn('subject_id', $subjectTeacherIds)->pluck('career_id')->toArray();

                if (!empty($careerIds)) {
                    $finalSubjectIds = CareerSubject::whereIn('career_id', $careerIds)->whereIn('subject_id', $subjectTeacherIds)->pluck('subject_id')->toArray();

                    if (!empty($finalSubjectIds)) {
                        $subjectsQuery->whereIn('id', $finalSubjectIds);
                    } else {
                        $subjectsQuery->whereNull('id');
                    }
                } else {
                    $subjectsQuery->whereNull('id');
                }
            } else {
                $subjectsQuery->whereNull('id');
            }
        }

        $subjects = $subjectsQuery->orderBy('subject_name', 'asc')->get();

        return Inertia('SpecificCompetences/Create', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecificCompetenceRequest $request)
    {
        SpecificCompetence::create($request->validated());
        $request->session()->flash('flash.banner', 'Competencia Específica creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('specificcompetences.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecificCompetence $specificcompetence)
    {
        $subjectsQuery = Subject::select('id', 'subject_name', 'subject_code');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                $careerIds = CareerSubject::whereIn('subject_id', $subjectTeacherIds)->pluck('career_id')->toArray();

                if (!empty($careerIds)) {
                    $finalSubjectIds = CareerSubject::whereIn('career_id', $careerIds)->whereIn('subject_id', $subjectTeacherIds)->pluck('subject_id')->toArray();

                    if (!empty($finalSubjectIds)) {
                        $subjectsQuery->whereIn('id', $finalSubjectIds);
                    } else {
                        $subjectsQuery->whereNull('id');
                    }
                } else {
                    $subjectsQuery->whereNull('id');
                }
            } else {
                $subjectsQuery->whereNull('id');
            }
        }

        $subjects = $subjectsQuery->orderBy('subject_name', 'asc')->get();

        return inertia('SpecificCompetences/Edit', [
            'specificcompetences' => $specificcompetence,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SpecificCompetenceRequest $request, SpecificCompetence $specificcompetence)
    {
        $specificcompetence->update($request->validated());
        $request->session()->flash('flash.banner', 'Competencia Específica actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('specificcompetences.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecificCompetence $specificcompetence)
    {
        $specificcompetence->delete();
        return redirect()->route('specificcompetences.index')->banner('Competencia Específica Eliminada con éxito!');
    }
}
