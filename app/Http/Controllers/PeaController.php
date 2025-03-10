<?php

namespace App\Http\Controllers;

use App\Models\Pea;
use App\Models\Career;
use App\Models\User;
use App\Models\CareerSubject;
use App\Models\SubjectTeacher;
use App\Models\AcademicPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\SubjectRequisite;
use App\Models\SpecificCompetence;
use App\Models\GenericCompetence;
use App\Models\TeachingLearningStrategy;
use App\Models\CurricularUnit;
use App\Models\TeachingContent;
use App\Models\Evaluation;
use App\Models\Bibliography;
use App\Models\Approval;
use App\Http\Requests\PeaRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        define('ELEMENTS_PER_PAGE', 10);
        $peas = Pea::with(['subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(ELEMENTS_PER_PAGE);

        return Inertia::render('Peas/Index', [
            'peas' => $peas->items(),
            'pagination' => [
                'total' => $peas->total(),
                'per_page' => $peas->perPage(),
                'current_page' => $peas->currentPage(),
                'last_page' => $peas->lastPage(),
                'from' => $peas->firstItem(),
                'to' => $peas->lastItem(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachersQuery = User::query();
        $subjects = Subject::all();
        $subjectst = Subject::select('id', 'subject_name', 'subject_code')->get();
        $careers = Career::all();
        $careerSubjects = CareerSubject::all();
        $academicperiods = AcademicPeriod::all();
        $subjectrequisites = SubjectRequisite::all();
        $specificcompetence = SpecificCompetence::all();
        $genericcompetences = GenericCompetence::all();
        $teachingLearningStrategies = TeachingLearningStrategy::all();
        $curricularunits = CurricularUnit::all();
        $teachingcontents = TeachingContent::all();
        $evaluations = Evaluation::all();
        $bibliographies = Bibliography::all();

        if (Auth::check()) {
            $teacherId = Auth::id();
            $teachersQuery->where('id', $teacherId);

            $subjectTeacherIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id');

            if ($subjectTeacherIds->isNotEmpty()) {
                $careerSubjectIds = CareerSubject::whereIn('subject_id', $subjectTeacherIds)->pluck('career_id');

                if ($careerSubjectIds->isNotEmpty()) {
                    $finalSubjectIds = CareerSubject::whereIn('career_id', $careerSubjectIds)->whereIn('subject_id', $subjectTeacherIds)->pluck('subject_id');
                    //dd($finalSubjectIds);
                    $subjects = Subject::whereIn('id', $finalSubjectIds)->get();
                    $careers = Career::whereIn('id', $careerSubjectIds)->get();
                    $careerSubjects = CareerSubject::whereIn('career_id', $careerSubjectIds)->whereIn('subject_id', $finalSubjectIds)->get();

                    // Filtrar academicperiods
                    $academicPeriodIds = Subject::whereIn('id', $finalSubjectIds)->pluck('academic_period_id')->unique()->toArray();
                    $academicperiods = AcademicPeriod::whereIn('id', $academicPeriodIds)->get();

                    // Filtrar subjectrequisites
                    $subjectrequisites = SubjectRequisite::whereIn('subject_id', $finalSubjectIds)->get();

                    // Filtrar specificcompetences
                    $specificcompetence = SpecificCompetence::whereIn('subject_id', $finalSubjectIds)->get();
                    // Filtrar genericcompetences
                    $genericcompetences = GenericCompetence::whereIn('subject_id', $finalSubjectIds)->get();
                    // Filtrar teachingLearningStrategies
                    $teachingLearningStrategies = TeachingLearningStrategy::whereIn('subject_id', $finalSubjectIds)->get();
                    // Filtrar curricularunits
                    $curricularunits = CurricularUnit::whereIn('subject_id', $finalSubjectIds)->orderBy('unit', 'asc')->get();

                    // Filtrar teachingcontents
                    $curricularUnitIds = $curricularunits->pluck('id')->toArray();
                    $teachingcontents = TeachingContent::whereIn('curricular_units_id', $curricularUnitIds)->get();
                    // Filtrar evaluations
                    $evaluations = Evaluation::whereIn('subject_id', $finalSubjectIds)->get();
                    // Filtrar bibliografia
                    $bibliographies = Bibliography::whereIn('subject_id', $finalSubjectIds)->get();
                }
            }
        }

        $teachers = $teachersQuery->select('id', 'name', 'email')->get();

        $vicerrectorado = User::whereHas('roles', function ($query) {
            $query->where('name', 'vicerrectorado');
        })
            ->select('id', 'name')
            ->get();

        return Inertia::render('Peas/Create', [
            'careers' => $careers,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'careersubjects' => $careerSubjects,
            'academicperiods' => $academicperiods,
            'subjectRequisite' => $subjectrequisites,
            'subjectst' => $subjectst,
            'specificcompetences' => $specificcompetence,
            'genericcompetences' => $genericcompetences,
            'teachinglearningstrategies' => $teachingLearningStrategies,
            'curricularunits' => $curricularunits,
            'teachingcontents' => $teachingcontents,
            'evaluations' => $evaluations,
            'bibliographies' => $bibliographies,
            'vicerrectorado' => $vicerrectorado,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeaRequest $request)
    {
        //dd($request->all());

        $details = $request->validated('details');

        // Reorganizar requisitos
        $requisitos = [];
        $maxLengthRequisitos = max(count($details['prerequisites']), count($details['prerequisites_code']), count($details['corequisites']), count($details['corequisites_code']));

        for ($i = 0; $i < $maxLengthRequisitos; $i++) {
            $requisitos[] = [
                'prerequisites' => $details['prerequisites'][$i] ?? null,
                'prerequisites_code' => $details['prerequisites_code'][$i] ?? null,
                'corequisites' => $details['corequisites'][$i] ?? null,
                'corequisites_code' => $details['corequisites_code'][$i] ?? null,
            ];
        }

        $details['requisitos'] = $requisitos;
        unset($details['prerequisites'], $details['prerequisites_code'], $details['corequisites'], $details['corequisites_code']);

        // Reorganizar requisitos
        $competenciasEspecificas = [];
        $maxLengthCompetenciasEspecificas = max(count($details['competence_specif_learningoutcomes']), count($details['competence_specif_contribution_level']));

        for ($i = 0; $i < $maxLengthCompetenciasEspecificas; $i++) {
            $competenciasEspecificas[] = [
                'competence_specif_learningoutcomes' => $details['competence_specif_learningoutcomes'][$i] ?? null,
                'competence_specif_contribution_level' => $details['competence_specif_contribution_level'][$i] ?? null,
            ];
        }

        $details['competencias_especificas'] = $competenciasEspecificas;
        unset($details['competence_specif_learningoutcomes'], $details['competence_specif_contribution_level']);

        // Reorganizar unidades curriculares
        $unidadesCurriculares = [];
        $maxLengthUnidades = max(count($details['curricularunit_unit']), count($details['curricularunit_name']), count($details['curricularunit_learning_outcomes']));

        for ($i = 0; $i < $maxLengthUnidades; $i++) {
            $unidadesCurriculares[] = [
                'unit' => $details['curricularunit_unit'][$i] ?? null,
                'name' => $details['curricularunit_name'][$i] ?? null,
                'learning_outcomes' => $details['curricularunit_learning_outcomes'][$i] ?? null,
            ];
        }

        $details['unidades_curriculares'] = $unidadesCurriculares;
        unset($details['curricularunit_unit'], $details['curricularunit_name'], $details['curricularunit_learning_outcomes']);

        // Reorganizar contenido de enseñanza
        $contenidoEnsenanza = [];
        $maxLengthContenido = max(count($details['teachingcontent_schedule']), count($details['teachingcontent_content']), count($details['teachingcontent_contact_teacher']), count($details['teachingcontent_contact_teacher_duration']), count($details['teachingcontent_practical_teaching']), count($details['teachingcontent_practical_teaching_duration']), count($details['teachingcontent_self_employment']), count($details['teachingcontent_self_employment_duration']), count($details['teachingcontent_practical_self_employment']), count($details['teachingcontent_practical_self_employment_duration']));

        for ($i = 0; $i < $maxLengthContenido; $i++) {
            $contenidoEnsenanza[] = [
                'schedule' => $details['teachingcontent_schedule'][$i] ?? null,
                'content' => $details['teachingcontent_content'][$i] ?? null,
                'contact_teacher' => $details['teachingcontent_contact_teacher'][$i] ?? null,
                'contact_teacher_duration' => $details['teachingcontent_contact_teacher_duration'][$i] ?? null,
                'practical_teaching' => $details['teachingcontent_practical_teaching'][$i] ?? null,
                'practical_teaching_duration' => $details['teachingcontent_practical_teaching_duration'][$i] ?? null,
                'self_employment' => $details['teachingcontent_self_employment'][$i] ?? null,
                'self_employment_duration' => $details['teachingcontent_self_employment_duration'][$i] ?? null,
                'practical_self_employment' => $details['teachingcontent_practical_self_employment'][$i] ?? null,
                'practical_self_employment_duration' => $details['teachingcontent_practical_self_employment_duration'][$i] ?? null,
            ];
        }

        $details['contenido_ensenanza'] = $contenidoEnsenanza;
        unset($details['teachingcontent_schedule'], $details['teachingcontent_content'], $details['teachingcontent_contact_teacher'], $details['teachingcontent_contact_teacher_duration'], $details['teachingcontent_practical_teaching'], $details['teachingcontent_practical_teaching_duration'], $details['teachingcontent_self_employment'], $details['teachingcontent_self_employment_duration'], $details['teachingcontent_practical_self_employment'], $details['teachingcontent_practical_self_employment_duration']);

        // Reorganizar evaluaciones
        $evaluaciones = [];
        $maxLengthEvaluaciones = max(count($details['evaluation_unit']), count($details['evaluation_Learning_outcomes']), count($details['evaluation_type']), count($details['evaluation_criteria']), count($details['evaluation_activity']), count($details['evaluation_first_partial']), count($details['evaluation_second_partial']));

        for ($i = 0; $i < $maxLengthEvaluaciones; $i++) {
            $evaluaciones[] = [
                'unit' => $details['evaluation_unit'][$i] ?? null,
                'learning_outcomes' => $details['evaluation_Learning_outcomes'][$i] ?? null,
                'type' => $details['evaluation_type'][$i] ?? null,
                'criteria' => $details['evaluation_criteria'][$i] ?? null,
                'activity' => $details['evaluation_activity'][$i] ?? null,
                'first_partial' => $details['evaluation_first_partial'][$i] ?? null,
                'second_partial' => $details['evaluation_second_partial'][$i] ?? null,
            ];
        }

        $details['evaluaciones'] = $evaluaciones;
        unset($details['evaluation_unit'], $details['evaluation_Learning_outcomes'], $details['evaluation_type'], $details['evaluation_criteria'], $details['evaluation_activity'], $details['evaluation_first_partial'], $details['evaluation_second_partial']);

        // Reorganizar bibliografía
        $bibliografia = [];
        $maxLengthBibliografia = max(count($details['bibliography_type']), count($details['bibliography_author']), count($details['bibliography_year']), count($details['bibliography_title']), count($details['bibliography_editorial']), count($details['bibliography_format']), count($details['bibliography_code']), count($details['bibliography_link']), count($details['bibliography_uplift']));

        for ($i = 0; $i < $maxLengthBibliografia; $i++) {
            $bibliografia[] = [
                'type' => $details['bibliography_type'][$i] ?? null,
                'author' => $details['bibliography_author'][$i] ?? null,
                'year' => $details['bibliography_year'][$i] ?? null,
                'title' => $details['bibliography_title'][$i] ?? null,
                'editorial' => $details['bibliography_editorial'][$i] ?? null,
                'format' => $details['bibliography_format'][$i] ?? null,
                'code' => $details['bibliography_code'][$i] ?? null,
                'link' => $details['bibliography_link'][$i] ?? null,
                'uplift' => $details['bibliography_uplift'][$i] ?? null,
            ];
        }

        $details['bibliografia'] = $bibliografia;
        unset($details['bibliography_type'], $details['bibliography_author'], $details['bibliography_year'], $details['bibliography_title'], $details['bibliography_editorial'], $details['bibliography_format'], $details['bibliography_code'], $details['bibliography_link'], $details['bibliography_uplift']);

        Pea::create([
            'subject_id' => $request->validated('subject_id'),
            'details' => $details, // Guardar los datos reorganizados
        ]);

        return redirect()->route('peas.index')->banner('PEA creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pea $pea)
    {
        return Inertia::render('Peas/Edit', ['pea' => $pea]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pea $pea)
    {
        $validated = $request->validate([]);

        $pea->update($validated);
        return redirect()->route('peas.index')->banner('PEA actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pea $pea)
    {
        $pea->delete();
        return redirect()->route('peas.index')->banner('PEA eliminado exitosamente.');
    }

    /**
     * Generate PEA in Word format.
     */

    /**
     * Generate PEA in PDF format.
     */
    public function generatePdf(Pea $pea)
    {
        $pdf = \PDF::loadView('pdf.pea', ['pea' => $pea]);
        return $pdf->download('pea_' . $pea->id . '.pdf');
    }
    public function getTemplate(): BinaryFileResponse
    {
        $templatePath = resource_path('templates/pea_template.docx');
        return response()->file($templatePath);
    }
    public function show(Request $request)
    {
        if ($request->has('career_id')) {
            if (Auth::check()) {
                $teacherId = Auth::id();
                $careerId = $request->input('career_id');

                try {
                    // Obtener los IDs de las asignaturas relacionadas con la carrera
                    $subjectIds = CareerSubject::where('career_id', $careerId)->pluck('subject_id');

                    // Obtener los IDs de las asignaturas relacionadas con el profesor autenticado
                    $teacherSubjectIds = SubjectTeacher::where('user_id', $teacherId)->pluck('subject_id');

                    // Intersección de los IDs de asignaturas (carrera y profesor)
                    $filteredSubjectIds = $subjectIds->intersect($teacherSubjectIds);

                    // Obtener las asignaturas filtradas
                    $subjects = Subject::whereIn('id', $filteredSubjectIds)->get();

                    $career = Career::find($careerId);

                    //return response()->json($subjects);
                    return response()->json([
                        'subjects' => $subjects,
                        'career' => $career,
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Error al obtener asignaturas'], 500);
                }
            }
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        } elseif ($request->has('subject_id')) {
            $subjectId = $request->input('subject_id');
            $subjectIdArray = [$subjectId];

            try {
                $subject = Subject::find($subjectId);

                if (!$subject) {
                    return response()->json(['error' => 'Asignatura no encontrada.'], 404);
                }

                $careersubject = CareerSubject::where('subject_id', $subjectId)->first();
                $academicperiods = AcademicPeriod::find($subject->academic_period_id);
                $subjectrequisites = SubjectRequisite::whereIn('subject_id', $subjectIdArray)->get();
                $specificcompetences = SpecificCompetence::whereIn('subject_id', $subjectIdArray)->get();
                $genericcompetences = GenericCompetence::whereIn('subject_id', $subjectIdArray)->get();
                $teachingLearningStrategies = TeachingLearningStrategy::whereIn('subject_id', $subjectIdArray)->get();
                $curricularunits = CurricularUnit::whereIn('subject_id', $subjectIdArray)->orderBy('unit', 'asc')->get();
                $curricularUnitIds = $curricularunits->pluck('id')->toArray();
                $teachingcontents = TeachingContent::whereIn('curricular_units_id', $curricularUnitIds)->get();
                $evaluations = Evaluation::whereIn('subject_id', $subjectIdArray)->get();
                $bibliographies = Bibliography::whereIn('subject_id', $subjectIdArray)->get();
                $approvals = Approval::where('subject_id', $subjectId)->first();

                if ($approvals) {
                    $teacher = User::find($approvals->teacher_id);
                    $coordinator = User::find($approvals->coordinator_id);
                    $vicerectorate = User::find($approvals->vice_rector_id);
                } else {
                    $teacher = null;
                    $coordinator = null;
                    $vicerectorate = null;
                }

                return response()->json([
                    'subject' => $subject,
                    'careersubject' => $careersubject,
                    'academicperiods' => $academicperiods,
                    'subjectrequisites' => $subjectrequisites,
                    'specificcompetences' => $specificcompetences,
                    'genericcompetences' => $genericcompetences,
                    'teachinglearningstrategies' => $teachingLearningStrategies,
                    'curricularunits' => $curricularunits,
                    'teachingcontents' => $teachingcontents,
                    'evaluations' => $evaluations,
                    'bibliographies' => $bibliographies,
                    'teacher' => $teacher,
                    'coordinator' => $coordinator,
                    'vicerectorate' => $vicerectorate,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al obtener datos de la asignatura: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'Parámetros inválidos'], 400);
        }
    }
}
