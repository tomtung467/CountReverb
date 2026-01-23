<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CounterController;

Route::get('/counter', [CounterController::class, 'getCount']);
Route::post('/counter/increment', [CounterController::class, 'increment']);
Route::post('/counter/reset', [CounterController::class, 'reset']);
