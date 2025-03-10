<?php

namespace App\Http\Controllers;

use App\Models\SubjectRequisite;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;
use Inertia\Inertia;
use App\Http\Requests\SubjectRequisiteRequest;

class SubjectRequisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);

        $subjectRequisitesQuery = SubjectRequisite::with(['subject', 'prerequisiteSubject', 'corequisiteSubject']);

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
                        $subjectRequisitesQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $subjectRequisitesQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $subjectRequisitesQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $subjectRequisitesQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $subjectRequisites = $subjectRequisitesQuery->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        return inertia('SubjectRequisites/Index', [
            'subjectRequisites' => $subjectRequisites->items(),
            'pagination' => [
                'total' => $subjectRequisites->total(),
                'per_page' => $subjectRequisites->perPage(),
                'current_page' => $subjectRequisites->currentPage(),
                'last_page' => $subjectRequisites->lastPage(),
                'from' => $subjectRequisites->firstItem(),
                'to' => $subjectRequisites->lastItem(),
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

        return inertia('SubjectRequisites/Create', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequisiteRequest $request)
    {
        SubjectRequisite::create($request->validated());
        $request->session()->flash('flash.banner', 'Requisitos de Asignatura creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjectrequisites.index');
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
    public function edit(SubjectRequisite $subjectrequisite)
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

        return inertia('SubjectRequisites/Edit', [
            'subjectRequisite' => $subjectrequisite,
            'subjects' => $subjects,
        ]);
        //return inertia('SubjectRequisites/Edit', ['subjectRequisite' => $subjectrequisite]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequisiteRequest $request, SubjectRequisite $subjectrequisite)
    {
        $subjectrequisite->update($request->validated());
        $request->session()->flash('flash.banner', 'Requisitos de Asignatura actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('subjectrequisites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectRequisite $subjectrequisite)
    {
        $subjectrequisite->delete();
        return redirect()->route('subjectrequisites.index')->banner('Requisitos de Asignatura Eliminada con éxito!');
    }

    public function getAvailableSubjects(Request $request)
    {
        $subjectId = $request->input('subject_id');
        //dd($subjectId);
        $prerequisiteId = $request->input('prerequisites');
        $corequisiteId = $request->input('corequisites');

        // Obtener todas las asignaturas
        $allSubjects = Subject::select('id', 'subject_name', 'subject_code')->orderBy('subject_name', 'asc')->get();

        // Si no hay asignatura seleccionada, devolver todas las asignaturas
        if (!$subjectId) {
            return response()->json($allSubjects);
        }

        // Obtener las asignaturas ya relacionadas
        $relatedSubjects = SubjectRequisite::where('subject_id', $subjectId)->orWhere('prerequisites', $subjectId)->orWhere('corequisites', $subjectId)->get();

        $relatedSubjectIds = $relatedSubjects
            ->flatMap(function ($requisite) {
                return [$requisite->prerequisites, $requisite->corequisites];
            })
            ->filter()
            ->unique()
            ->toArray();

        // Excluir la asignatura principal y las asignaturas ya relacionadas
        $availableSubjects = $allSubjects->reject(function ($subject) use ($subjectId, $relatedSubjectIds) {
            return $subject->id == $subjectId || in_array($subject->id, $relatedSubjectIds);
        });

        // Excluir la asignatura seleccionada en prerequisito si se esta consultando correquisito
        if ($corequisiteId !== null && $prerequisiteId) {
            $availableSubjects = $availableSubjects->reject(function ($subject) use ($prerequisiteId) {
                return $subject->id == $prerequisiteId;
            });
        }

        // Excluir la asignatura seleccionada en correquisito si se esta consultando prerequisito
        if ($prerequisiteId !== null && $corequisiteId) {
            $availableSubjects = $availableSubjects->reject(function ($subject) use ($corequisiteId) {
                return $subject->id == $corequisiteId;
            });
        }

        return response()->json($availableSubjects);
    }
}
