<?php

namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class SpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voices = $this->getSpeakersFromApi();
        foreach ($voices as $voice) {
            Speaker::create([
                'name' => $voice->name,
                'description' => $voice->labels->description ?? null,
                'api_id' => $voice->voice_id,
                'gender'=> $voice->labels->gender,
                'use_case' => $voice->labels->{'use case'} ?? null,
                'age_description' => $voice->labels->age,
                'language' => $voice->labels->accent ?? 'en',
            ]);
        }
        Speaker::create([
            'name' => 'Martin Dupont Profond',
            'description' => 'deep',
            'api_id' => 'wyZnrAs18zdIj8UgFSV8',
            'gender'=> 'male',
            'use_case' => 'narrative_story',
            'age_description' => 'middle_age',
            'language' => 'fr',
        ]);
    }

    public function getSpeakersFromApi(): array
    {
        $response = Http::withHeaders([
            'xi-api-key' => env('ELEVEN_LABS_API_T2S_KEY'),
        ])->withOptions([
            'verify' => false,
        ])->get('https://api.elevenlabs.io/v1/text-to-speech/');

        if($response->failed()) {
            return [];
        }

        return json_decode($response->body())->voices;
    }
}
