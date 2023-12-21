<?php

use Hatchet\Segment\Http\Controllers\SegmentRelayController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::prefix('segment/')->group(function () {
    Route::get('segment.js', function () {
        $file = dirname(__DIR__).'/resources/segment.min.js';
        $file = file_get_contents($file) ?: '';

        return response()->make($file, headers: [
            'content-type' => 'text/javascript',
        ]);
    });

    Route::post('page', [SegmentRelayController::class, 'page']);
    Route::post('track', [SegmentRelayController::class, 'track']);

    Route::prefix('proxies/')->group(function () {
        Route::get('cdn/v1/projects/{id}/settings', function () {
            $json = Cache::remember('segment_settings', now()->addHour(), function () {
                $id = config('segment.key');

                $json = Http::acceptJson()
                    ->get("https://cdn.segment.com/v1/projects/{$id}/settings")
                    ->json();

                return $json;
            });

            return response()->json($json);
        });

        Route::prefix('api/')->withoutMiddleware(VerifyCsrfToken::class)->group(function () {
            Route::post('i', [SegmentRelayController::class, 'identify']);
            Route::post('p', [SegmentRelayController::class, 'page']);
            Route::post('t', [SegmentRelayController::class, 'track']);
        });
    });
});
