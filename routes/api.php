<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CareerSubjectController; // Certifique-se de que o caminho está correto
use App\Http\Controllers\SubjectTeacherController; // Certifique-se de que o caminho está correto
use App\Http\Controllers\SubjectRequisiteController; // Certifique-se de que o caminho está correto

//Route::get('/peas/subjects', [PeaController::class, 'getCareerSubjects'])->middleware('auth:sanctum'); // O auth:api
Route::get('/test', function () {
    dd('Test route reached');
});

Route::get('/careersubjects/available-subjects', [CareerSubjectController::class, 'getAvailableSubjects']);

Route::get('/subjectteachers/available-subjects', [SubjectTeacherController::class, 'getAvailableSubjects']);
Route::get('/subjectrequisites/available-subjects', [SubjectRequisiteController::class, 'getAvailableSubjects']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
