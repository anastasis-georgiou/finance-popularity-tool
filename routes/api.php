<?php

use App\Http\Controllers\TweetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\HandleController;
use App\Http\Controllers\InstrumentController;

Route::apiResource('instruments', InstrumentController::class);
Route::apiResource('handles', HandleController::class);
Route::apiResource('tweets', TweetController::class);
Route::get('/top-instruments/{period}', [StatsController::class, 'topInstruments']);
Route::get('/top-instrument/{period}', [StatsController::class, 'topInstruments']);
