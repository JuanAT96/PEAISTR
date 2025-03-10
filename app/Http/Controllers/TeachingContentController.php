<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeachingContent;
use App\Models\CurricularUnit;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\TeachingContentRequest;
use App\Models\Subject;
use App\Models\CareerSubject;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectTeacher;

class TeachingContentController extends Controller
{
    public function index()
    {
        define('NUMERO_ELEMENTOS_POR_PAGINA', 10);
        $teachingContentsQuery = TeachingContent::with('curricularunit');

        if (Auth::check() && Auth::user()->hasRole('docente')) {
            $teacherId = Auth::id();
            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id')->toArray();

            if (!empty($subjectTeacherIds)) {
                $curricularUnitIds = CurricularUnit::whereIn('subject_id', $subjectTeacherIds)->pluck('id')->toArray();

                if (!empty($curricularUnitIds)) {
                    $teachingContentsQuery->whereIn('curricular_units_id', $curricularUnitIds);
                } else {
                    $teachingContentsQuery->whereNull('curricular_units_id');
                }
            } else {
                $teachingContentsQuery->whereNull('curricular_units_id');
            }
        }

        $teachingcontents = $teachingContentsQuery->orderBy('curricular_units_id', 'asc')->paginate(NUMERO_ELEMENTOS_POR_PAGINA);

        $teachingContentsItems = $teachingcontents->items();

        // Obtener los curricular_units_id de los teaching_contents
        $curricularUnitIds = collect($teachingContentsItems)->pluck('curricular_units_id')->unique()->toArray();

        // Obtener los subject_id de los curricular_units
        $subjectIds = CurricularUnit::whereIn('id', $curricularUnitIds)->pluck('subject_id')->unique()->toArray();

        // Obtener los subjects correspondientes
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        return inertia('TeachingContents/Index', [
            'teaching_contents' => $teachingContentsItems,
            'subjects' => $subjects, // Incluir los subjects en la respuesta
            'pagination' => [
                'total' => $teachingcontents->total(),
                'per_page' => $teachingcontents->perPage(),
                'current_page' => $teachingcontents->currentPage(),
                'last_page' => $teachingcontents->lastPage(),
                'from' => $teachingcontents->firstItem(),
                'to' => $teachingcontents->lastItem(),
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
        $usedCurricularUnitIds = TeachingContent::pluck('curricular_units_id')->toArray();

        $curricularUnits = $curricularUnitsQuery
            ->whereNotIn('id', $usedCurricularUnitIds) // Excluir unidades curriculares usadas
            ->orderBy('unit', 'asc')
            ->get();

        // Obtener los subject_id de los curricular_units filtrados
        $subjectIds = $curricularUnits->pluck('subject_id')->unique()->toArray();

        // Obtener los subjects correspondientes
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        return Inertia('TeachingContents/Create', [
            'curricular_units' => $curricularUnits,
            'subjects' => $subjects,
        ]);
    }

    public function store(TeachingContentRequest $request)
    {
        TeachingContent::create($request->validated());
        $request->session()->flash('flash.banner', 'Contenido de Enseñanza creada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('teachingcontents.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(TeachingContent $teachingcontent)
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
        $usedCurricularUnitIds = TeachingContent::pluck('curricular_units_id')->toArray();

        // Excluir la unidad curricular actual de la lista de excluidos
        $usedCurricularUnitIds = array_diff($usedCurricularUnitIds, [$teachingcontent->curricular_units_id]);

        $curricularUnits = $curricularUnitsQuery
            ->whereNotIn('id', $usedCurricularUnitIds) // Excluir unidades curriculares usadas
            ->orderBy('unit', 'asc')
            ->get();
        // Obtener los subject_id de los curricular_units filtrados
        $subjectIds = $curricularUnits->pluck('subject_id')->unique()->toArray();

        // Obtener los subjects correspondientes
        $subjects = Subject::whereIn('id', $subjectIds)->get();

        return inertia('TeachingContents/Edit', [
            'teaching_contents' => $teachingcontent,
            'curricular_units' => $curricularUnits,
            'subjects' => $subjects,
        ]);
    }

    public function update(TeachingContentRequest $request, TeachingContent $teachingcontent)
    {
        $teachingcontent->update($request->validated());
        $request->session()->flash('flash.banner', 'Contenido de Enseñanza actualizada con éxito!');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('teachingcontents.index');
    }

    public function destroy(TeachingContent $teachingcontent)
    {
        $teachingcontent->delete();
        return redirect()->route('teachingcontents.index')->banner('Contenido de Enseñanza Eliminado con éxito!');
    }
}
