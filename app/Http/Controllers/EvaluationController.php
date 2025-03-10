<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Subject;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\EvaluationRequest;
use App\Models\CurricularUnit;

use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class EvaluationController extends Controller
{
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $evaluationsQuery = Evaluation::with(['subject', 'cunit']);

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
                        $evaluationsQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $evaluationsQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $evaluationsQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $evaluationsQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $evaluations = $evaluationsQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$evaluations = Evaluation::with('subject')
            ->orderBy('subject_id', 'asc')
            ->paginate(numero_elementos_por_pagina);*/
        return inertia('Evaluations/Index', [
            'evaluations' => $evaluations->items(),
            'pagination' => [
                'total' => $evaluations->total(),
                'per_page' => $evaluations->perPage(),
                'current_page' => $evaluations->currentPage(),
                'last_page' => $evaluations->lastPage(),
                'from' => $evaluations->firstItem(),
                'to' => $evaluations->lastItem(),
            ],
        ]);
    }

    public function create()
    {
        $curricularUnitsQuery = CurricularUnit::select('id', 'subject_id', 'unit', 'learning_outcomes');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                $curricularUnitsQuery->whereIn('subject_id', $subjectTeacherIds);
            } else {
                $curricularUnitsQuery->whereNull('subject_id');
            }
        }

        // Obtener los IDs de unidades curriculares que ya tienen teaching content
        $usedCurricularUnitIds = Evaluation::pluck('unit')->toArray();

        $curricularUnits = $curricularUnitsQuery
            ->whereNotIn('id', $usedCurricularUnitIds) // Excluir unidades curriculares usadas
            ->orderBy('unit', 'asc')
            ->get();

        // Obtener los subject_id de los curricular_units filtrados
        $subjectIds = $curricularUnits->pluck('subject_id')->unique()->toArray();

        // Obtener los subjects correspondientes
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        return Inertia('Evaluations/Create', [
            'subjects' => $subjects,
            'curricularunits' => $curricularUnits,
        ]);
    }

    public function store(EvaluationRequest $request)
    {
        //dd($request->all());
        Evaluation::create($request->validated());
        $request->session()->flash('flash.banner', 'Evaluación creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('evaluations.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Evaluation $evaluation)
    {
        $curricularUnitsQuery = CurricularUnit::select('id', 'subject_id', 'unit', 'learning_outcomes');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                $curricularUnitsQuery->whereIn('subject_id', $subjectTeacherIds);
            } else {
                $curricularUnitsQuery->whereNull('subject_id');
            }
        }

        // Obtener los IDs de unidades curriculares que ya tienen evaluaciones
        $usedCurricularUnitIds = Evaluation::pluck('unit')->toArray();

        // Obtener las unidades curriculares filtradas y excluyendo las usadas
        $curricularUnits = $curricularUnitsQuery->whereNotIn('id', $usedCurricularUnitIds)->orderBy('unit', 'asc')->get();

        // Agregar la unidad curricular actual si no está en la lista
        if (!$curricularUnits->contains('id', $evaluation->unit)) {
            $curricularUnits->push(CurricularUnit::find($evaluation->unit));
        }

        // Obtener los subject_id de los curricular_units filtrados
        $subjectIds = $curricularUnits->pluck('subject_id')->unique()->toArray();

        // Obtener los subjects correspondientes
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        // Agregar la asignatura actual si no está en la lista
        if (!$subjects->contains('id', $evaluation->subject_id)) {
            $subjects->push(Subject::find($evaluation->subject_id));
        }

        return inertia('Evaluations/Edit', [
            'evaluation' => $evaluation,
            'subjects' => $subjects,
            'curricularunits' => $curricularUnits,
        ]);
    }

    public function update(EvaluationRequest $request, Evaluation $evaluation)
    {
        $evaluation->update($request->validated());
        $request->session()->flash('flash.banner', 'Evaluación actualizado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('evaluations.index');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return redirect()->route('evaluations.index')->banner('Evaluación Eliminado con éxito!');
    }
}
