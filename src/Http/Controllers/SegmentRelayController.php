<?php

namespace Hatchet\Segment\Http\Controllers;

use Hatchet\Segment\Facades\Segment;
use Hatchet\Segment\Http\Requests\SegmentIdentityRequest;
use Hatchet\Segment\Http\Requests\SegmentPageRequest;
use Hatchet\Segment\Http\Requests\SegmentTrackRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

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
