<?php

namespace SegmentTrap\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use SegmentTrap\Facades\Segment;
use SegmentTrap\Http\Requests\SegmentIdentityRequest;
use SegmentTrap\Http\Requests\SegmentPageRequest;
use SegmentTrap\Http\Requests\SegmentTrackRequest;

class SegmentRelayController extends Controller
{
    public function identify(SegmentIdentityRequest $request): JsonResponse
    {
        $request->verify();

        Segment::driver()->identify($request->json()->all());

        return Response::json([
            'success' => true,
        ]);
    }

    public function page(SegmentPageRequest $request): JsonResponse
    {
        $request->verify();

        Segment::driver()->page($request->json()->all());

        return Response::json([
            'success' => true,
        ]);
    }

    public function track(SegmentTrackRequest $request): JsonResponse
    {
        $request->verify();

        Segment::driver()->track($request->json()->all());

        return Response::json([
            'success' => true,
        ]);
    }
}
