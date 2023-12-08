<?php

namespace SegmentTrap\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use SegmentTrap\Facades\Segment;
use SegmentTrap\Http\Requests\SegmentIdentityRequest;
use SegmentTrap\Http\Requests\SegmentPageRequest;
use SegmentTrap\Http\Requests\SegmentTrackRequest;
use SegmentTrap\Identity\SegmentUser;

class SegmentRelayController extends Controller
{
    public function identify(SegmentIdentityRequest $request): JsonResponse
    {
        $request->verify();

        /** @var ?string $guard */
        $guard = $request->input('guard');

        $user = Auth::guard($guard)->user();
        $user = SegmentUser::session()->set($user);

        return Response::json([
            'success' => true,
        ]);
    }

    public function page(SegmentPageRequest $request): JsonResponse
    {
        $request->verify();

        Segment::driver()->page($request->json());

        return Response::json([
            'success' => true,
        ]);
    }

    public function track(SegmentTrackRequest $request): JsonResponse
    {
        $request->verify();

        Segment::driver()->track($request->json());

        return Response::json([
            'success' => true,
        ]);
    }
}
