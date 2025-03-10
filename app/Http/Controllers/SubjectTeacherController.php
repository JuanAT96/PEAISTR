<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectTeacher;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\SubjectTeacherRequest;

class SubjectTeacherController extends Controller
{
    public function index()
    {
        define('numero_elementos_por_pagina', 100);
        $subjectteachers = SubjectTeacher::with(['subject', 'user'])
            ->orderBy('user_id', 'asc')
            ->paginate(numero_elementos_por_pagina);
        return inertia('SubjectTeachers/Index', [
            'subjectteachers' => $subjectteachers->items(),
            'pagination' => [
                'total' => $subjectteachers->total(),
                'per_page' => $subjectteachers->perPage(),
                'current_page' => $subjectteachers->currentPage(),
                'last_page' => $subjectteachers->lastPage(),
                'from' => $subjectteachers->firstItem(),
                'to' => $subjectteachers->lastItem(),
            ],
        ]);
    }

    public function create()
    {
        $subjects = Subject::select('id', 'subject_name', 'subject_code')->orderBy('subject_name', 'asc')->get();
        //$teachers = User::select('id', 'name')->get();
        // Obtener solo los usuarios que no tienen el rol "estudiante"
        $teachers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'estudiante');
        })
            ->select('id', 'name')
            ->get();

        return Inertia('SubjectTeachers/Create', [
            'subjects' => $subjects,
            'teachers' => $teachers,
        ]);
    }

    public function store(SubjectTeacherRequest $request)
    {
        SubjectTeacher::create($request->validated());
        $request->session()->flash('flash.banner', 'Asignación Asignatura/Docente creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjectteachers.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(SubjectTeacher $subjectteacher)
    {
        $subjects = Subject::select('id', 'subject_name', 'subject_code')->orderBy('subject_name', 'asc')->get();
        $teachers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'estudiante');
        })
            ->select('id', 'name')
            ->get();

        return inertia('SubjectTeachers/Edit', [
            'subjectteacher' => $subjectteacher,
            'subjects' => $subjects,
            'teachers' => $teachers,
        ]);
    }

    public function update(SubjectTeacherRequest $request, SubjectTeacher $subjectteacher)
    {
        $subjectteacher->update($request->validated());
        $request->session()->flash('flash.banner', 'Asignación Asignatura/Docente actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjectteachers.index');
    }

    public function destroy(SubjectTeacher $subjectteacher)
    {
        $subjectteacher->delete();
        return redirect()->route('subjectteachers.index')->banner('Asignación Asignatura/Docente Eliminado con éxito!');
    }

    public function getAvailableSubjects(Request $request)
    {
        $userId = $request->input('user_id');

        $assignedSubjects = SubjectTeacher::where('user_id', $userId)->pluck('subject_id')->toArray();

        $availableSubjects = Subject::whereNotIn('id', $assignedSubjects)->select('id', 'subject_name', 'subject_code')->orderBy('subject_name', 'asc')->get();

        return response()->json($availableSubjects);
    }
}
