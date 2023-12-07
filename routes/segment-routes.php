<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use SegmentTrap\Http\Controllers\SegmentRelayController;

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
            return response()->json([
                "integrations" => [
                    "Segment.io" => [
                        "apiKey" => "BuH0QawS5Fr2bidLTfrGZ09YqL5nes4A",
                        "unbundledIntegrations" => [],
                        "addBundledMetadata" => true,
                        "maybeBundledConfigIds" => (object) [],
                        "versionSettings" => [ "version" => "4.4.7", "componentTypes" => ["browser"] ]
                    ]
                ],
                "plan" => [
                    "track" => [ "__default" => [ "enabled" => true, "integrations" => (object) [] ] ],
                    "identify" => [ "__default" => [ "enabled" => true ] ],
                    "group" => [ "__default" => [ "enabled" => true ] ]
                ],
                "edgeFunction" => (object) [],
                "analyticsNextEnabled" => true,
                "middlewareSettings" => (object) [],
                "enabledMiddleware" => (object) [],
                "metrics" => [ "sampleRate" => 0.1 ],
                "legacyVideoPluginsEnabled" => false,
                "remotePlugins" => []
              ]);
        });

        Route::prefix('api/')->group(function () {
            Route::post('i', [SegmentRelayController::class, 'identify']);
            Route::post('p', [SegmentRelayController::class, 'page']);
            Route::post('t', [SegmentRelayController::class, 'track']);
        })->withoutMiddleware(VerifyCsrfToken::class);
    });
});
