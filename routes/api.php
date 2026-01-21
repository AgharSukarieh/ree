<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AnalyticalSkillController;
use App\Http\Controllers\Api\CertificationController;
use App\Http\Controllers\Api\CoreCompetencyController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\MedicalSkillController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ResearchController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SoftSkillController;
use App\Http\Controllers\Api\WishController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// User Routes
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{qr_id}', [UserController::class, 'show']);
    Route::put('/{qr_id}', [UserController::class, 'update']);
    Route::delete('/{qr_id}', [UserController::class, 'destroy']);
    Route::get('/{qr_id}/profile', [UserController::class, 'profile']);
});

// Activity Routes
Route::prefix('activities')->group(function () {
    Route::get('/', [ActivityController::class, 'index']);
    Route::post('/', [ActivityController::class, 'store']);
    Route::get('/{id}', [ActivityController::class, 'show']);
    Route::put('/{id}', [ActivityController::class, 'update']);
    Route::delete('/{id}', [ActivityController::class, 'destroy']);
    Route::get('/user/{qr_id}', [ActivityController::class, 'byUser']);
});

// Analytical Skills Routes
Route::prefix('analytical-skills')->group(function () {
    Route::get('/', [AnalyticalSkillController::class, 'index']);
    Route::post('/', [AnalyticalSkillController::class, 'store']);
    Route::get('/{id}', [AnalyticalSkillController::class, 'show']);
    Route::put('/{id}', [AnalyticalSkillController::class, 'update']);
    Route::delete('/{id}', [AnalyticalSkillController::class, 'destroy']);
});

// Certification Routes
Route::prefix('certifications')->group(function () {
    Route::get('/', [CertificationController::class, 'index']);
    Route::post('/', [CertificationController::class, 'store']);
    Route::get('/{id}', [CertificationController::class, 'show']);
    Route::put('/{id}', [CertificationController::class, 'update']);
    Route::delete('/{id}', [CertificationController::class, 'destroy']);
});

// Core Competency Routes
Route::prefix('core-competencies')->group(function () {
    Route::get('/', [CoreCompetencyController::class, 'index']);
    Route::post('/', [CoreCompetencyController::class, 'store']);
    Route::get('/{id}', [CoreCompetencyController::class, 'show']);
    Route::put('/{id}', [CoreCompetencyController::class, 'update']);
    Route::delete('/{id}', [CoreCompetencyController::class, 'destroy']);
});

// Experience Routes
Route::prefix('experiences')->group(function () {
    Route::get('/', [ExperienceController::class, 'index']);
    Route::post('/', [ExperienceController::class, 'store']);
    Route::get('/{id}', [ExperienceController::class, 'show']);
    Route::put('/{id}', [ExperienceController::class, 'update']);
    Route::delete('/{id}', [ExperienceController::class, 'destroy']);
});

// Interest Routes
Route::prefix('interests')->group(function () {
    Route::get('/', [InterestController::class, 'index']);
    Route::post('/', [InterestController::class, 'store']);
    Route::get('/{id}', [InterestController::class, 'show']);
    Route::put('/{id}', [InterestController::class, 'update']);
    Route::delete('/{id}', [InterestController::class, 'destroy']);
});

// Language Routes
Route::prefix('languages')->group(function () {
    Route::get('/', [LanguageController::class, 'index']);
    Route::post('/', [LanguageController::class, 'store']);
    Route::get('/{id}', [LanguageController::class, 'show']);
    Route::put('/{id}', [LanguageController::class, 'update']);
    Route::delete('/{id}', [LanguageController::class, 'destroy']);
});

// Medical Skill Routes
Route::prefix('medical-skills')->group(function () {
    Route::get('/', [MedicalSkillController::class, 'index']);
    Route::post('/', [MedicalSkillController::class, 'store']);
    Route::get('/{id}', [MedicalSkillController::class, 'show']);
    Route::put('/{id}', [MedicalSkillController::class, 'update']);
    Route::delete('/{id}', [MedicalSkillController::class, 'destroy']);
});

// Membership Routes
Route::prefix('memberships')->group(function () {
    Route::get('/', [MembershipController::class, 'index']);
    Route::post('/', [MembershipController::class, 'store']);
    Route::get('/{id}', [MembershipController::class, 'show']);
    Route::put('/{id}', [MembershipController::class, 'update']);
    Route::delete('/{id}', [MembershipController::class, 'destroy']);
});

// Project Routes
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
});

// Research Routes
Route::prefix('research')->group(function () {
    Route::get('/', [ResearchController::class, 'index']);
    Route::post('/', [ResearchController::class, 'store']);
    Route::get('/{id}', [ResearchController::class, 'show']);
    Route::put('/{id}', [ResearchController::class, 'update']);
    Route::delete('/{id}', [ResearchController::class, 'destroy']);
});

// Skill Routes
Route::prefix('skills')->group(function () {
    Route::get('/', [SkillController::class, 'index']);
    Route::post('/', [SkillController::class, 'store']);
    Route::get('/{id}', [SkillController::class, 'show']);
    Route::put('/{id}', [SkillController::class, 'update']);
    Route::delete('/{id}', [SkillController::class, 'destroy']);
});

// Soft Skill Routes
Route::prefix('soft-skills')->group(function () {
    Route::get('/', [SoftSkillController::class, 'index']);
    Route::post('/', [SoftSkillController::class, 'store']);
    Route::get('/{id}', [SoftSkillController::class, 'show']);
    Route::put('/{id}', [SoftSkillController::class, 'update']);
    Route::delete('/{id}', [SoftSkillController::class, 'destroy']);
});

// Wish Routes
Route::prefix('wishes')->group(function () {
    Route::get('/', [WishController::class, 'index']);
    Route::post('/', [WishController::class, 'store']);
    Route::get('/{id}', [WishController::class, 'show']);
    Route::put('/{id}', [WishController::class, 'update']);
    Route::delete('/{id}', [WishController::class, 'destroy']);
});
