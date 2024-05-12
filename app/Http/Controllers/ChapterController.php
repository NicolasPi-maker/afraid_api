<?php

namespace App\Http\Controllers;

use App\Helpers\OpenAIQueryHelper;
use App\Models\Chapter;
use App\Models\Illustration;
use App\Models\Paragraphe;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function insert(Request $request, string $storyId): JsonResponse
    {
        $request->validate([
            'generated_story' => 'required|array',
        ]);

        $generatedStory = $request->get('generated_story');
        $storedStory = Story::find($storyId);

        try {
            $this->storeChapters($generatedStory, $storedStory);
            return response()->json([
                'success' => true,
                'data' => $storedStory,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function storeChapters($generatedStory, $storedStory): void
    {
        foreach ($generatedStory['chapters'] as $chapter) {
            $storedChapter = $this->storeChapter($chapter, $storedStory->id);
            $this->generateStoryIllustrations($generatedStory, $storedChapter->id, $chapter);
            foreach ($chapter['paragraphs'] as $paragraph) {
                Paragraphe::create([
                    'content' => $paragraph['content'],
                    'order' => $paragraph['order'],
                    'chapter_id' => $storedChapter->id,
                ]);
            }
        }
    }

    private function storeChapter($chapter, string $storyId): Chapter
    {
        return Chapter::create([
            'title' => $chapter['title'],
            'story_id' => $storyId,
            'order' => $chapter['number'],
        ]);
    }

    private function generateStoryIllustrations($story, string $chapterId, $chapter): void
    {
        $illustration = OpenAIQueryHelper::generateIllustrationFromDalle($chapter['illustration']);
        $file = file_get_contents($illustration);
        $storagePath = 'illustrations/' . $story['title'] . '/' . $chapter['title'] . '.jpg';
        Storage::disk('public')->put($storagePath, $file);
        Illustration::create([
            'filename' => $chapter['title'],
            'alt' => $chapter['title'],
            'extension' => 'jpg',
            'chapter_id' => $chapterId,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        //
    }
}
