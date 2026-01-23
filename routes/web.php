<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CounterController;

Route::get('/', [CounterController::class, 'index']);
Route::get('/counter', [CounterController::class, 'index']);
