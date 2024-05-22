<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\OpenAIQueryHelper;
use App\Models\Prompt;
use App\Models\Teaser;
use App\Models\Thumbnail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeaserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $teasers = Teaser::all()->sortBy('created_at');
        $teasers->load('thumbnails');
        return response()->json([
            'success' => true,
            'data' => $teasers,
        ]);
    }

    public function insert(Request $request): JsonResponse
    {
        $request->validate([
            'prompt_id' => 'required|int',
            'story_id' => 'required|int',
            'teaser' => 'required|array',
        ]);

        $prompt_id = $request->get('prompt_id');
        $story_id = $request->get('story_id');
        $teaser = $request->get('teaser');

        try {
            $storedTeaser = $this->storeTeaser($teaser, $prompt_id, $story_id);
            $this->storeTeaserThumbnail($storedTeaser, $teaser['illustration']['url']);
            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function storeTeaser($teaser, string $promptId, string $storyId): Teaser
    {
        return Teaser::create([
            'title' => $teaser['title'],
            'content' => $teaser['content'],
            'prompt_id' => $promptId,
            'story_id' => $storyId,
            'user_id' => 1,
        ]);
    }

    private function storeTeaserThumbnail($teaser, $thumbnail): void
    {
        $file = file_get_contents($thumbnail);
        try {
            $filename = 'teaser_' . $teaser->title;
            $storagePath = 'thumbnails/' . $teaser->title . '/' . $filename . '.jpg';
            Storage::disk('public')->put($storagePath, $file);
            Thumbnail::create([
                'filename' => $filename,
                'alt' => $teaser->title,
                'extension' => 'jpg',
                'teaser_id' => $teaser->id,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Generate teaser.
     */
    public function generate(Request $request): JsonResponse
    {
//        $user = AuthHelper::getUserFromToken($request->bearerToken());
        $request->validate([
            'prompt' => 'required|string',
            'language' => 'required|string',
        ]);

        $prompt = $request->get('prompt');
        $language = $request->get('language');

        try {
            $teaser = OpenAIQueryHelper::generateTeaserFromOpenAI($prompt, $language);
            $illustration = OpenAIQueryHelper::generateIllustrationFromDalle($teaser->illustration);
            $teaser->illustration = [
                'description' => $teaser->illustration,
                'url' => $illustration,
            ];
    //        $this->savePrompt($request->get('prompt'), $user->id);
            return response()->json([
                'success' => true,
                'teaser' => $teaser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'teaser' => $teaser,
            ], 500);
        }
    }

    public function savePrompt(string $prompt, int $user_id): void
    {
        Prompt::create([
            'content' => $prompt,
            'user_id' => $user_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teaserId): JsonResponse
    {
        try {
            $teaser = Teaser::with('thumbnails')->find($teaserId);
            return response()->json([
                'success' => true,
                'data' => $teaser,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teaser $teaser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teaser $teaser)
    {
        //
    }
}
