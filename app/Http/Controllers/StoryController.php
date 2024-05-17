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
//        $request->validate([
//            'prompt' => 'required|string',
//            'teaser' => 'required|array',
//            'speaker' => 'required|array',
//            'language' => 'required|array',
//        ]);
//        $prompt = $request->get('prompt');
//        $teaser = $request->get('teaser');
//        $speaker = $request->get('speaker');
//        $language = $request->get('language');
//        try {
//            $generatedStory = OpenAIQueryHelper::generateStoryFromOpenAI($prompt, $teaser['title'], $teaser['content'], $language['code']);
//            $storedPrompt = $this->storePrompt($prompt);
//            $storedStory = $this->storeStory($generatedStory);
//            $storedStory->languages()->attach($language['id']);
//
//            return response()->json([
//                'success' => true,
//                'data' => [
//                    'story_id' => $storedStory->id,
//                    'prompt_id' => $storedPrompt->id,
//                    'teaser' => $teaser,
//                    'generated_story' => $generatedStory,
//                ],
//            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => $e->getMessage(),
//            ], 500);
//        }
        return response()->json([
            'success' => false,
            'message' => 'Not implemented yet',
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
