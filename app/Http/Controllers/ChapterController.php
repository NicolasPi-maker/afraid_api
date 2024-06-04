<?php

namespace App\Http\Controllers;

use App\Helpers\OpenAIQueryHelper;
use App\Jobs\TransferTmpFileToS3;
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
        $storedStory = Story::where('id', $storyId)->with('chapters')->first();

        try {
            $this->storeChapters($generatedStory, $storedStory);
            return response()->json([
                'success' => true,
                'data' => $generatedStory,
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
        $chapterNumber = $storedStory->chapters->count() + 1;
        foreach ($generatedStory['chapters'] as $chapter) {
            $chapter['number'] = $chapterNumber;
            $storedChapter = $this->storeChapter($chapter, $storedStory->id);
            $this->generateStoryIllustrations($storedStory, $storedChapter->id, $chapter);
            foreach ($chapter['paragraphs'] as $paragraph) {
                Paragraphe::create([
                    'content' => $paragraph['content'],
                    'order' => $paragraph['order'],
                    'chapter_id' => $storedChapter->id,
                ]);
            }
            $chapterNumber++;
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
        $disk = 'illustrations';
        $storagePath = $story['title'] . '/' . $chapter['title'] . '.jpg';
        try {
            $illustrationUrl = OpenAIQueryHelper::generateIllustrationFromDalle($chapter['illustration']);
            $illustration = Illustration::create([
                'filename' => $chapter['title'],
                'alt' => $chapter['title'],
                'extension' => 'jpg',
                'chapter_id' => $chapterId,
                'url' => $illustrationUrl,
            ]);
            try {
                TransferTmpFileToS3::dispatch($disk, $storagePath, $illustrationUrl, $illustration);
            } catch (\Exception $e) {
                throw new \Exception('Failed to save thumbnail to disk: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            throw new \Exception('Error while storing illustration: ' . $e->getMessage());
        }
    }
}
