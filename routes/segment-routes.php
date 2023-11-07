<?php

use Illuminate\Support\Facades\Route;
use SegmentTrap\Http\Controllers\SegmentRelayController;

Route::prefix('segment/')->group(function () {
    Route::get('segment.js', function () {
        $file = dirname(__DIR__).'/resources/segment.js';
        $file = file_get_contents($file) ?: '';

        return response()->make($file, headers: [
            'content-type' => 'text/javascript',
        ]);
    });

    Route::post('page', [SegmentRelayController::class, 'page']);
    Route::post('track', [SegmentRelayController::class, 'track']);
});
