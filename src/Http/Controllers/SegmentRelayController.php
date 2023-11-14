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
        /** @var string $name */
        $name = $request->input('name');

        /** @var string $category */
        $category = $request->input('category');

        /** @var array<string, mixed> $properties */
        $properties = $request->input('properties', []);

        Segment::driver()->page([
            'name' => $name,
            'category' => $category,
            'properties' => $properties,
        ]);

        return Response::json([
            'success' => true,
        ]);
    }

    public function track(SegmentTrackRequest $request): JsonResponse
    {
        /** @var string $event */
        $event = $request->input('event');

        /** @var array<string, mixed> $properties */
        $properties = $request->input('properties');

        Segment::driver()->page([
            'event' => $event,
            'properties' => $properties,
        ]);

        return Response::json([
            'success' => true,
        ]);
    }
}
