<?php

use App\Jobs\CrawlTweetsJob;
use App\Models\Handle;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;

Route::get('/top-instruments/{period}', [StatsController::class, 'topInstruments']);

Route::get('/test-redis/{id}', function ($id) {
    CrawlTweetsJob::dispatch(Handle::where('id', $id)->firstOrFail());
    return 'Job dispatched!';
})->where(['id' => '[0-9]+']);
