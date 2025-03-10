<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\Subject;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\ApprovalRequest;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class ApprovalController extends Controller
{
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $approvalsQuery = Approval::with(['subject', 'teacher', 'coordinator', 'viceRector']);

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
                        $approvalsQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $approvalsQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $approvalsQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $approvalsQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $approvals = $approvalsQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$approvals = Approval::with(['subject', 'teacher', 'coordinator', 'viceRector'])
            ->orderBy('id', 'asc')
            ->paginate(numero_elementos_por_pagina);*/
        return inertia('Approvals/Index', [
            'approvals' => $approvals->items(),
            'pagination' => [
                'total' => $approvals->total(),
                'per_page' => $approvals->perPage(),
                'current_page' => $approvals->currentPage(),
                'last_page' => $approvals->lastPage(),
                'from' => $approvals->firstItem(),
                'to' => $approvals->lastItem(),
            ],
        ]);
    }

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

        // Obtener las IDs de las asignaturas ya registradas en approvals
        $approvedSubjectIds = Approval::pluck('subject_id')->toArray();

        // Excluir las asignaturas ya aprobadas
        $subjects = $subjectsQuery->whereNotIn('id', $approvedSubjectIds)->orderBy('subject_name', 'asc')->get();

        // Obtener los usuarios que tienen el rol "docente"
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'docente');
        })
            ->select('id', 'name')
            ->get();

        // Obtener los usuarios que tienen el rol "coordinador"
        $coordinators = User::whereHas('roles', function ($query) {
            $query->where('name', 'coordinador');
        })
            ->select('id', 'name')
            ->get();

        // Obtener los usuarios que tienen el rol "vicerrectorado"
        $viceRectors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vicerrectorado');
        })
            ->select('id', 'name')
            ->get();

        return Inertia::render('Approvals/Create', [
            'subjects' => $subjects,
            'teachers' => $teachers,
            'coordinators' => $coordinators,
            'vice_rectors' => $viceRectors,
        ]);
    }

    public function store(ApprovalRequest $request)
    {
        Approval::create($request->validated());
        $request->session()->flash('flash.banner', 'Aprobación creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('approvals.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Approval $approval)
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
        // Obtener los usuarios que tienen el rol "docente"
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'docente');
        })
            ->select('id', 'name')
            ->get();

        // Obtener los usuarios que tienen el rol "coordinador"
        $coordinators = User::whereHas('roles', function ($query) {
            $query->where('name', 'coordinador');
        })
            ->select('id', 'name')
            ->get();

        // Obtener los usuarios que tienen el rol "vicerrectorado"
        $viceRectors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vicerrectorado');
        })
            ->select('id', 'name')
            ->get();

        return inertia('Approvals/Edit', [
            'approval' => $approval,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'coordinators' => $coordinators,
            'vice_rectors' => $viceRectors,
        ]);
    }

    public function update(ApprovalRequest $request, Approval $approval)
    {
        $approval->update($request->validated());
        $request->session()->flash('flash.banner', 'Aprobación actualizado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('approvals.index');
    }

    public function destroy(Approval $approval)
    {
        $approval->delete();
        return redirect()->route('approvals.index')->banner('Aprobación Eliminado con éxito!');
    }
}
