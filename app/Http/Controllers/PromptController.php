<?php

namespace App\Http\Controllers;

use App\Helpers\OpenAIQueryHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    public function generateRandomPrompt(Request $request): JsonResponse
    {
        $request->validate([
            'language' => 'required|string',
        ]);
        try {
            $prompt = OpenAIQueryHelper::generateRandomPrompt($request->get('language'));
            return response()->json([
                'success' => true,
                'data' => $prompt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
