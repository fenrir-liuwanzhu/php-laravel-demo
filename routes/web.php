<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\PageController;

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

Route::get('/register',  [PageController::class, 'showRegister']);
Route::post('/register', [PageController::class, 'doRegister']);

Route::get('/login',  [PageController::class, 'showLogin'])->name('login');
Route::post('/login', [PageController::class, 'doLogin']);
Route::post('/logout',[PageController::class, 'logout']);
Route::get('/dashboard', [PageController::class, 'showDashboard'])->middleware('auth');

