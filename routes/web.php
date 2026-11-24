<?php

use App\Http\Controllers\LearnController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/learn', [LearnController::class, 'index']);
Route::get('/learn2', [LearnController::class, 'day2']);
Route::match(['get', 'post'], '/learn3', [LearnController::class, 'day3']);
Route::get('/learn4', [LearnController::class, 'day4']);
Route::get('/learn5', [LearnController::class, 'day5']);
Route::get('/learn6', [LearnController::class, 'day6']);

Route::get('/hello/{name}', [PageController::class, 'hello']);
Route::get('/greeting/{name}', [PageController::class, 'greeting']);

Route::get('/register', [PageController::class, 'showRegister']);
Route::post('/register', [PageController::class, 'doRegister']);

Route::get('/login', [PageController::class, 'showLogin'])->name('login');
Route::post('/login', [PageController::class, 'doLogin']);
Route::post('/logout', [PageController::class, 'logout']);
Route::get('/dashboard', [PageController::class, 'showDashboard'])->middleware('auth');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{user}', [UserController::class, 'update']);
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
    Route::get('/user/avatar', [UserController::class, 'showAvatar']);
    Route::post('/user/avatar', [UserController::class, 'uploadAvatar']);
    Route::get('/user/avatar/download', [UserController::class, 'downloadAvatar']);
    Route::get('/user/pdf', [UserController::class, 'showPdf']);
    Route::get('/documents/{name}/preview', [UserController::class, 'previewPdf']);
    Route::get('/documents/{name}/download', [UserController::class, 'downloadPdf']);
});
