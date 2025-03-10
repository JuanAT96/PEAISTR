<?php

namespace App\Http\Controllers;

use App\Models\TeachingLearningStrategy;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\TeachingLearningStrategyRequest;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class TeachingLearningStrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $teachingLearningStrategiesQuery = TeachingLearningStrategy::with('subject');

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
                        $teachingLearningStrategiesQuery->whereIn('subject_id', $finalSubjectIds);
                    } else {
                        $teachingLearningStrategiesQuery->whereNull('subject_id'); // Si no hay materias relacionadas, no mostrar nada.
                    }
                } else {
                    $teachingLearningStrategiesQuery->whereNull('subject_id'); // Si no hay carreras relacionadas, no mostrar nada.
                }
            } else {
                $teachingLearningStrategiesQuery->whereNull('subject_id'); // Si no hay materias del docente, no mostrar nada.
            }
        }

        $teachingLearningStrategies = $teachingLearningStrategiesQuery->orderBy('id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        /*$teachingLearningStrategies = TeachingLearningStrategy::with('subject')
            ->orderBy('id', 'asc')
            ->paginate(NUMERO_ELEMENTOS_POR_PAGINA);*/
        return inertia('TeachingLearningStrategies/Index', [
            'teachingLearningStrategies' => $teachingLearningStrategies->items(),
            'pagination' => [
                'total' => $teachingLearningStrategies->total(),
                'per_page' => $teachingLearningStrategies->perPage(),
                'current_page' => $teachingLearningStrategies->currentPage(),
                'last_page' => $teachingLearningStrategies->lastPage(),
                'from' => $teachingLearningStrategies->firstItem(),
                'to' => $teachingLearningStrategies->lastItem(),
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

        return Inertia('TeachingLearningStrategies/Create', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeachingLearningStrategyRequest $request)
    {
        TeachingLearningStrategy::create($request->validated());
        $request->session()->flash('flash.banner', 'Estrategias Metodologías De Enseñanza - Aprendizaje creado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('teachinglearningstrategies.index');
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
    public function edit(TeachingLearningStrategy $teachinglearningstrategy)
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

        return inertia('TeachingLearningStrategies/Edit', [
            'teachingLearningStrategy' => $teachinglearningstrategy,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeachingLearningStrategyRequest $request, TeachingLearningStrategy $teachinglearningstrategy)
    {
        $teachinglearningstrategy->update($request->validated());
        $request->session()->flash('flash.banner', 'Estrategias Metodologías De Enseñanza - Aprendizaje actualizado con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('teachinglearningstrategies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeachingLearningStrategy $teachinglearningstrategy)
    {
        $teachinglearningstrategy->delete();
        return redirect()->route('teachinglearningstrategies.index')->banner('Estrategias Metodologías De Enseñanza - Aprendizaje Eliminado con éxito!');
    }
}
