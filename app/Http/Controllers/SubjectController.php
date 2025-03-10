<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\AcademicPeriod;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Requests\SubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 25);

        $subjectsQuery = Subject::with('academicperiods')->orderBy('subject_code', 'asc');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();
            $subjectsQuery->whereIn('id', $subjectTeacherIds);
        }

        $subjects = $subjectsQuery->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        return inertia('Subjects/Index', [
            'subjects' => $subjects->items(),
            'pagination' => [
                'total' => $subjects->total(),
                'per_page' => $subjects->perPage(),
                'current_page' => $subjects->currentPage(),
                'last_page' => $subjects->lastPage(),
                'from' => $subjects->firstItem(),
                'to' => $subjects->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todas las asignaturas para el select
        $academicperiods = AcademicPeriod::select('id', 'name', 'estado')->get();

        //dd($subjects); // Depuración: Verificar que los sujetos se obtienen correctamente
        // Renderizar la vista con Inertia
        return Inertia('Subjects/Create', [
            'academicperiods' => $academicperiods,
        ]);
        //return inertia('Subjects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        Subject::create($request->validated());
        $request->session()->flash('flash.banner', 'Asignatura creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjects.index');
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
    public function edit(Subject $subject)
    {
        // Obtener todas las asignaturas para el select
        $academicperiods = AcademicPeriod::select('id', 'name', 'estado')->get();
        return Inertia('Subjects/Edit', [
            'subject' => $subject,
            'academicperiods' => $academicperiods,
        ]);
        // return inertia('Subjects/Edit', ['subject' => $subject]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        $request->session()->flash('flash.banner', 'Asignatura actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->banner('Asignatura Eliminada con éxito!');
    }
}
