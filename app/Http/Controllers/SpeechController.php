<?php

namespace App\Http\Controllers;

use App\Helpers\ElevenLabsT2SQueryHelper;
use App\Models\Speech;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeechController extends Controller
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
    public function insert(Request $request): JsonResponse
    {
        $request->validate([
            'story_id' => 'required|int',
            'speaker' => 'required|array',
            'language' => 'required|array',
        ]);

        $story = Story::with('chapters.paragraphs')->find($request->get('story_id'));
        $speaker = $request->get('speaker');
        $language = $request->get('language');

        try{
            $this->generateStorySpeech($story, $speaker, $language);
            return response()->json([
                'success' => true,
                'message' => 'Speech generated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateStorySpeech($story, $speaker, $language): void
    {
        $storyFullText = ElevenLabsT2SQueryHelper::getFullTextFromStory($story);
        $voiceSettings = [
            "stability" => 0.5,
            "similarity_boost" => 0.5
        ];
        $speechFile = ElevenLabsT2SQueryHelper::generateTextToSpeech($storyFullText, $speaker['api_id'], $voiceSettings);

        $filename = $story->title . '_' . $language['code'];
        $storagePath = 'speeches/' .$story->title .'/'. $filename . '.mp3';

        Storage::disk('public')->put($storagePath, $speechFile);
        $this->storeSpeech($story, $speaker['id'], $language['id'], $filename);
    }

    private function storeSpeech(Story $story, string $speakerId, string $languageId, string $filename): void
    {
        Speech::create([
            'filename' => $filename,
            'extension' => 'mp3',
            'story_id' => $story->id,
            'speaker_id' => $speakerId,
            'language_id' => $languageId,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Speech $speech)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Speech $speech)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Speech $speech)
    {
        //
    }
}
