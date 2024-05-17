<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\JsonResponse;

class SpeakerController extends Controller
{
    public function index(): JsonResponse
    {
        $speakers = Speaker::all();
        return response()->json([
            'success' => true,
            'data' => $speakers,
        ]);
    }
}
