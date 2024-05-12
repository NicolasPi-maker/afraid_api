<?php

namespace App\Http\Controllers;

use App\Helpers\ElevenLabsT2SQueryHelper;
use App\Helpers\OpenAIQueryHelper;
use App\Models\Chapter;
use App\Models\Illustration;
use App\Models\Paragraphe;
use App\Models\Prompt;
use App\Models\Speech;
use App\Models\Story;
use App\Models\Teaser;
use App\Models\Thumbnail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function show(string $id): JsonResponse
    {
        try {
            $story = Story::with('chapters.paragraphs', 'chapters.illustration', 'speeches', 'teasers', 'teasers.thumbnails', 'languages')->find($id);
            return response()->json([
                'success' => true,
                'data' => $story,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function insert(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string',
            'teaser' => 'required|array',
            'speaker' => 'required|array',
            'language' => 'required|array',
        ]);
        $prompt = $request->get('prompt');
        $teaser = $request->get('teaser');
        $speaker = $request->get('speaker');
        $language = $request->get('language');
        try {
            $generatedStory = OpenAIQueryHelper::generateStoryFromOpenAI($prompt, $teaser['title'], $teaser['content'], $language['code']);
            $storedPrompt = $this->storePrompt($prompt);
            $storedStory = $this->storeStory($generatedStory);
            $storedStory->languages()->attach($language['id']);

            return response()->json([
                'success' => true,
                'data' => [
                    'story_id' => $storedStory->id,
                    'prompt_id' => $storedPrompt->id,
                    'teaser' => $teaser,
                    'generated_story' => $generatedStory,
                ],
            ]);
//            $storedTeaser = $this->storeTeaser($teaser, $storedPrompt->id, $storedStory->id);
//            $this->storeTeaserThumbnail($storedTeaser, $teaser['illustration']['url']);
//            foreach ($generatedStory->chapters as $chapter) {
//                $storedChapter = $this->storeChapter($chapter, $storedStory->id);
//                $this->generateStoryIllustrations($generatedStory, $storedChapter->id, $chapter);
//                foreach ($chapter->paragraphs as $paragraph) {
//                    Paragraphe::create([
//                        'content' => $paragraph->content,
//                        'order' => $paragraph->order,
//                        'chapter_id' => $storedChapter->id,
//                    ]);
//                }
//            }
//            $this->generateStorySpeech($storedStory, $speaker, $language);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateStoryIllustrations($story, string $chapterId, $chapter): void
    {
        $illustration = OpenAIQueryHelper::generateIllustrationFromDalle($chapter->illustration);
        $file = file_get_contents($illustration);
        try {
            $storagePath = 'illustrations/' . $story->title . '/' . $chapter->title . '.jpg';
            Storage::disk('public')->put($storagePath, $file);
            Illustration::create([
                'filename' => $chapter->title,
                'alt' => $chapter->title,
                'extension' => 'jpg',
                'chapter_id' => $chapterId,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
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

    private function storeStory($story): Story
    {
        return Story::create([
            'title' => $story->title,
            'user_id' => 1,
        ]);
    }

    private function storePrompt($prompt): Prompt
    {
        return Prompt::create([
            'content' => $prompt,
        ]);
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

    private function storeChapter($chapter, string $storyId): Chapter
    {
        return Chapter::create([
            'title' => $chapter->title,
            'story_id' => $storyId,
            'order' => $chapter->number,
        ]);
    }

    /**
     * Leonardo Ai prompt requirements
     * 1. Subject of the prompt with lot of details (who, what, where, when)
     * 2. Media style (photo, painting, drawing, manga, digital art, etc.)
     * 3. The Style (comic, realistic, abstract, etc.)  and artist style (Picasso, Van Gogh, etc.)
     * 4. Resolution (size of the image)
     * 5. Mood (happy, sad, horror, etc.)
     * 6. Color (black and white, color, etc.)
     * 7. Shading (Three-point lighting, Butterfly Lightning, backlighting, studio lighting, etc.)
     * 8. Angle of view (bird's eye view, worm's eye view, etc.)
     *
     */
}
