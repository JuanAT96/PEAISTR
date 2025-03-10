<?php

namespace App\Http\Controllers;

use App\Models\GenericCompetence;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\GenericCompetenceRequest;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class GenericCompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);

        $genericcompetencesQuery = GenericCompetence::with('subject');

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
                        $genericcompetencesQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $genericcompetencesQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $genericcompetencesQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $genericcompetencesQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $genericcompetences = $genericcompetencesQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$genericcompetences = GenericCompetence::with('subject')
            ->orderBy('id', 'asc')
            ->paginate(NUMERO_ELEMENTOS_POR_PAGINA);*/
        return inertia('GenericCompetences/Index', [
            'genericCompetences' => $genericcompetences->items(),
            'pagination' => [
                'total' => $genericcompetences->total(),
                'per_page' => $genericcompetences->perPage(),
                'current_page' => $genericcompetences->currentPage(),
                'last_page' => $genericcompetences->lastPage(),
                'from' => $genericcompetences->firstItem(),
                'to' => $genericcompetences->lastItem(),
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

        return Inertia('GenericCompetences/Create', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenericCompetenceRequest $request)
    {
        GenericCompetence::create($request->validated());
        $request->session()->flash('flash.banner', 'Competencia Generica creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('genericcompetences.index');
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
    public function edit(GenericCompetence $genericcompetence)
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

        return inertia('GenericCompetences/Edit', [
            'genericCompetence' => $genericcompetence,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenericCompetenceRequest $request, GenericCompetence $genericcompetence)
    {
        $genericcompetence->update($request->validated());
        $request->session()->flash('flash.banner', 'Competencia Generica actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('genericcompetences.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GenericCompetence $genericcompetence)
    {
        $genericcompetence->delete();
        return redirect()->route('genericcompetences.index')->banner('Competencia genérica Eliminada con éxito!');
    }
}
