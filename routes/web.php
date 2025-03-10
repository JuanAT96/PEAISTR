<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BibliographyController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\CurricularUnitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LearningOutcomeController;
use App\Http\Controllers\PeaDocumentController;
use App\Http\Controllers\PeaModuleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectTeacherController;
use App\Http\Controllers\TeachingContentController;
use App\Http\Controllers\TeachingStrategyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerSubjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\SubjectRequisiteController;
use App\Http\Controllers\SpecificCompetenceController;
use App\Http\Controllers\GenericCompetenceController;
use App\Http\Controllers\TeachingLearningStrategyController;
use App\Http\Controllers\PeaController;

//use Inertia\Inertia;

//Rutas no autenticadas
Route::get('/', [DashboardController::class, 'index']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    //Rutas autenticadas
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('/careers', CareerController::class);
    Route::post('/careers/{career}', [CareerController::class, 'update'])->name('careers.update'); // Cambiar a POST
    Route::resource('/users', UserController::class);
    Route::resource('/subjects', SubjectController::class);
    Route::resource('/careersubjects', CareerSubjectController::class);

    Route::resource('/curricularunits', CurricularUnitController::class);

    //Route::resource('/learningoutcomes', LearningOutcomeController::class);

    //  Route::resource('/competencies', CompetencyController::class);
    //    Route::resource('/teachingstrategies', TeachingStrategyController::class);
    Route::resource('/teachingcontents', TeachingContentController::class);
    Route::resource('/evaluations', EvaluationController::class);
    Route::resource('/bibliographies', BibliographyController::class);
    Route::resource('/approvals', ApprovalController::class);
    Route::resource('/subjectteachers', SubjectTeacherController::class);
    Route::resource('/roles', RoleController::class);

    Route::resource('/reports', ReportController::class);
    Route::resource('/config-system', CareerController::class);

    Route::resource('/academicperiods', AcademicPeriodController::class);
    Route::resource('/subjectrequisites', SubjectRequisiteController::class);
    Route::resource('/specificcompetences', SpecificCompetenceController::class);
    Route::resource('/genericcompetences', GenericCompetenceController::class);
    Route::resource('/teachinglearningstrategies', TeachingLearningStrategyController::class);
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::resource('/peas', PeaController::class);
    Route::post('/peas/generate/word', [PeaController::class, 'generateWord'])->name('peas.generate.word');
    Route::get('/peas/template/word', [PeaController::class, 'getTemplate'])->name('peas.template.word');
});
