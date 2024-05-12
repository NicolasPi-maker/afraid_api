<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index():JsonResponse
    {
        $languages = Language::all();
        return response()->json([
            'success' => true,
            'data' => $languages,
        ]);
    }
}
