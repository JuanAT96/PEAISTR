<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bibliography;
use App\Models\Subject;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\BibliographyRequest;

use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class BibliographyController extends Controller
{
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $bibliographiesQuery = Bibliography::with(['subject']);

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
                        $bibliographiesQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $bibliographiesQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $bibliographiesQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $bibliographiesQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $bibliographies = $bibliographiesQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$bibliographies = Bibliography::with('subject')
            ->orderBy('id', 'asc')
            ->paginate(numero_elementos_por_pagina);*/
        return inertia('Bibliographies/Index', [
            'bibliographies' => $bibliographies->items(),
            'pagination' => [
                'total' => $bibliographies->total(),
                'per_page' => $bibliographies->perPage(),
                'current_page' => $bibliographies->currentPage(),
                'last_page' => $bibliographies->lastPage(),
                'from' => $bibliographies->firstItem(),
                'to' => $bibliographies->lastItem(),
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

        $subjects = $subjectsQuery->orderBy('subject_name', 'asc')->get();

        return Inertia('Bibliographies/Create', [
            'subjects' => $subjects,
        ]);
    }

    public function store(BibliographyRequest $request)
    {
        Bibliography::create($request->validated());
        $request->session()->flash('flash.banner', 'Bibliografía creado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('bibliographies.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Bibliography $bibliography)
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

        return inertia('Bibliographies/Edit', [
            'bibliography' => $bibliography,
            'subjects' => $subjects,
        ]);
    }

    public function update(BibliographyRequest $request, Bibliography $bibliography)
    {
        $bibliography->update($request->validated());
        $request->session()->flash('flash.banner', 'Bibliografía actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('bibliographies.index');
    }

    public function destroy(Bibliography $bibliography)
    {
        $bibliography->delete();
        return redirect()->route('bibliographies.index')->banner('Bibliografía Eliminado con éxito!');
    }
}
