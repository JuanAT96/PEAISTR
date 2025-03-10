<?php

namespace App\Http\Controllers;

use App\Models\CurricularUnit;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\CurricularUnitRequest;

use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class CurricularUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $curricularunits = CurricularUnit::all();
    return Inertia::render('CurricularUnits/Index', [
        'CurricularUnits' => $curricularunits
    ]);*/
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $curricularunitsQuery = CurricularUnit::with('subject');

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
                        $curricularunitsQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $curricularunitsQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $curricularunitsQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $curricularunitsQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $curricularunits = $curricularunitsQuery
            ->orderBy('Subject_id', 'asc')
            ->orderBy('unit', 'asc')
            ->paginate(NUMERO_ELEMENTOS_POR_PAGINA);
        /*$curricularunits = CurricularUnit::with('subject')
            ->orderBy('name', 'asc')
            ->paginate(numero_elementos_por_pagina);*/
        return inertia('CurricularUnits/Index', [
            'curricular_units' => $curricularunits->items(),
            'pagination' => [
                'total' => $curricularunits->total(),
                'per_page' => $curricularunits->perPage(),
                'current_page' => $curricularunits->currentPage(),
                'last_page' => $curricularunits->lastPage(),
                'from' => $curricularunits->firstItem(),
                'to' => $curricularunits->lastItem(),
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

        return Inertia('CurricularUnits/Create', [
            'subjects' => $subjects,
        ]);
        //return inertia('CurricularUnits/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @param App\Http\Request\CurricularUnitRequest
     * @return App\Http\Request\Response
     */
    public function store(CurricularUnitRequest $request)
    {
        CurricularUnit::create($request->validated());
        $request->session()->flash('flash.banner', 'Unidad Curricular creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('curricularunits.index');
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
    public function edit(CurricularUnit $curricularunit)
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

        return inertia('CurricularUnits/Edit', [
            'curricular_units' => $curricularunit,
            'subjects' => $subjects,
        ]);
        //        return inertia('CurricularUnits/Edit', ['curricular_unit' => $curricularunit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurricularUnitRequest $request, CurricularUnit $curricularunit)
    {
        $curricularunit->update($request->validated());
        $request->session()->flash('flash.banner', 'Unidad Curricular actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('curricularunits.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CurricularUnit $curricularunit)
    {
        // Eliminar resultados de aprendizaje relacionados
        //$curricularunits->learningoutcome()->delete();

        // Eliminar contenidos de enseñanza relacionados
        //$curricularunits->teachingcontent()->delete();

        // Eliminar la unidad curricular
        $curricularunit->delete();
        return redirect()->route('curricularunits.index')->banner('Unidad Curricular Eliminado con éxito!');
    }
}
