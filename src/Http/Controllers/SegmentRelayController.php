<?php

namespace SegmentTrap\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use SegmentTrap\Facades\Segment;
use SegmentTrap\Identity\SegmentUser;

class SegmentRelayController extends Controller
{
    public function identify(Request $request): JsonResponse
    {
        $user = Auth::user($request->input('guard'));
        $user = SegmentUser::session()->set($user);

        return response()->json([
            'success' => true,
        ]);
    }

    public function page(Request $request): JsonResponse
    {
        Segment::driver()->page([
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'properties' => $request->input('properties'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function track(Request $request): JsonResponse
    {
        Segment::driver()->page([
            'event' => $request->input('event'),
            'properties' => $request->input('properties'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
