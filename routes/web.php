<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearnController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/learn', [LearnController::class, 'index']);
Route::get('/learn2', [LearnController::class, 'day2']);
Route::match(['get', 'post'], '/learn3', [LearnController::class, 'day3']);
Route::get('/learn4', [LearnController::class, 'day4']);