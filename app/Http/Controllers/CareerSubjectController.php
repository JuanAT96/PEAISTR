<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerSubject;
use App\Models\Career;
use App\Models\Subject;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\CareerSubjectRequest;

class CareerSubjectController extends Controller
{
    public function index()
    {
        define('numero_elementos_por_pagina', 100);
        $careerSubjects = CareerSubject::with(['career', 'subject'])
            ->orderBy('career_id', 'asc')
            ->paginate(numero_elementos_por_pagina);
        return inertia('CareerSubjects/Index', [
            'career_subjects' => $careerSubjects->items(),
            'pagination' => [
                'total' => $careerSubjects->total(),
                'per_page' => $careerSubjects->perPage(),
                'current_page' => $careerSubjects->currentPage(),
                'last_page' => $careerSubjects->lastPage(),
                'from' => $careerSubjects->firstItem(),
                'to' => $careerSubjects->lastItem(),
            ],
        ]);
    }

    public function create()
    {
        $careers = Career::select('id', 'name', 'duration')->get();

        // Obtener las IDs de las asignaturas que ya están asignadas a carreras
        $assignedSubjectIds = CareerSubject::pluck('subject_id')->toArray();

        // Obtener las asignaturas que NO están en la lista de asignadas
        $subjects = Subject::whereNotIn('id', $assignedSubjectIds)->select('id', 'subject_name', 'subject_code')->get();

        return Inertia('CareerSubjects/Create', [
            'careers' => $careers,
            'subjects' => $subjects,
        ]);
    }

    public function store(CareerSubjectRequest $request)
    {
        CareerSubject::create($request->validated());
        $request->session()->flash('flash.banner', 'Asignación Carrera/Asignatura creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('careersubjects.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(CareerSubject $careersubject)
    {
        // Obtener las IDs de las asignaturas que ya están asignadas a carreras
        $assignedSubjectIds = CareerSubject::pluck('subject_id')->toArray();

        // Obtener la asignatura actual que se está editando
        $currentSubject = Subject::find($careersubject->subject_id);

        // Obtener las asignaturas que NO están en la lista de asignadas
        $subjects = Subject::whereNotIn('id', $assignedSubjectIds)->select('id', 'subject_name')->get();

        // Agregar la asignatura actual a la lista de asignaturas
        $subjects->push($currentSubject);

        $careers = Career::select('id', 'name')->get();

        return inertia('CareerSubjects/Edit', [
            'career_subjects' => $careersubject,
            'careers' => $careers,
            'subjects' => $subjects,
        ]);
    }

    public function update(CareerSubjectRequest $request, CareerSubject $careersubject)
    {
        $careersubject->update($request->validated());
        $request->session()->flash('flash.banner', 'Asignación Carrera/Asignatura actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('careersubjects.index');
    }

    public function destroy(CareerSubject $careersubject)
    {
        $careersubject->delete();
        return redirect()->route('careersubjects.index')->banner('Asignación Carrera/Asignatura Eliminado con éxito!');
    }

    public function getAvailableSubjects(Request $request)
    {
        $careerId = $request->input('career_id');
        $subjectId = $request->input('subject_id'); // Obtener el subject_id si existe

        // Obtener las IDs de las asignaturas que ya están asignadas a carreras
        $assignedSubjects = CareerSubject::pluck('subject_id')->toArray();

        // Obtener la asignatura actual
        $currentSubject = Subject::find($subjectId);

        // Excluir la asignatura actual de la lista de asignaturas asignadas
        if ($currentSubject) {
            $assignedSubjects = array_diff($assignedSubjects, [$currentSubject->id]);
        }

        // Obtener las asignaturas que NO están en la lista de asignadas
        $availableSubjects = Subject::whereNotIn('id', $assignedSubjects)->select('id', 'subject_name', 'subject_code')->get();

        // Si se proporciona un subject_id, obtener la asignatura actual y agregarla a la lista
        /*if ($subjectId) {
            $currentSubject = Subject::find($subjectId);
              if ($currentSubject && !$availableSubjects->contains('id', $currentSubject->id)) {
                $availableSubjects->push($currentSubject);
            }
        }*/

        return response()->json($availableSubjects);
    }
}
