<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\RegisterController;

// Test route to verify Laravel is working
Route::get('/test-laravel', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working correctly!',
        'app_name' => config('app.name'),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
    ]);
});

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
Route::get('/profile/{qr_id}', [WebController::class, 'profile'])->name('profile');

// Registration routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/register/success', [RegisterController::class, 'success'])->name('register.success');

// OpenAI Form Filler routes
Route::post('/api/openai/fill-form', [\App\Http\Controllers\OpenAIController::class, 'fillForm'])->name('openai.fill-form');
Route::get('/openai/response', [\App\Http\Controllers\OpenAIController::class, 'showResponse'])->name('openai.response');

// Test registration page
Route::get('/register-test', function () {
    return view('register_test');
})->name('register.test');

// Test functions page
Route::get('/test-functions', function () {
    return view('test_functions');
})->name('test.functions');

// Complete registration page
Route::get('/register-complete', function () {
    return view('register_complete');
})->name('register.complete');

// Test medical sections page
Route::get('/test-medical', function () {
    return view('test_medical');
})->name('test.medical');

// Test add buttons page
Route::get('/test-add-buttons', function () {
    return view('test_add_buttons');
})->name('test.add.buttons');

// Test basic sections page
Route::get('/test-basic-sections', function () {
    return view('test_basic_sections');
})->name('test.basic.sections');

// Debug sections page
Route::get('/debug-sections', function () {
    return view('debug_sections');
})->name('debug.sections');

// Test language toggle page
Route::get('/test-language', function () {
    return view('test_language');
})->name('test.language');

// EVE Robot page
Route::get('/eve-robot', function () {
    return view('eve_robot');
})->name('eve.robot');