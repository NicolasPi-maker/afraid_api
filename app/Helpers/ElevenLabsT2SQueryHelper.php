<?php

namespace App\Helpers;

use App\Builders\ElevenLabsT2SQueryBuilder;
use Illuminate\Support\Facades\Http;

class ElevenLabsT2SQueryHelper
{
    public function __construct() {}

    private static function textToSpeechQuery(array $query, string $voiceId)
    {
        $response = Http::withHeaders([
            'Accept' => 'audio/mpeg',
            'Content-Type' => 'application/json',
            'xi-api-key' => env('ELEVEN_LABS_API_T2S_KEY'),
        ])->withOptions([
            'verify' => false,
        ])->post(env('ELEVEN_LABS_API_T2S_URL').$voiceId, $query);
        return $response->body();
    }

    public static function generateTextToSpeech(string $text, string $voiceId, array $voiceSettings)
    {
        $queryBuilder = new ElevenLabsT2SQueryBuilder();
        $query = $queryBuilder
            ->setText($text)
            ->setVoiceSettings($voiceSettings)
            ->build();
        return self::textToSpeechQuery($query, $voiceId);
    }

    public static function getFullTextFromStory($story): string
    {
        $fullText = '';
        $story->load('chapters.paragraphs');
        foreach ($story->chapters as $chapter) {
            $fullText .= $chapter->title . "\n";
            foreach ($chapter->paragraphs as $paragraph) {
                $fullText .= $paragraph->content . "\n";
            }
        }
        return $fullText;
    }
}
