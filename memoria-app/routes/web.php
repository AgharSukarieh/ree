<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\RegisterController;

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
Route::get('/profile/{qr_id}', [WebController::class, 'profile'])->name('profile');

// Registration routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

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