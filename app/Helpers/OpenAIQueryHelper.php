<?php

namespace App\Helpers;
use App\Builders\OpenAiDalleQueryBuilder;
use App\Builders\OpenAiQueryBuilder;
use Illuminate\Support\Facades\Http;

Class OpenAIQueryHelper
{
    public function __construct() {}
    private static function chatGPT(array $query)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->post(env('OPENAI_API_TEXTGEN_URL'), $query);
            $decodedResponse = json_decode($response->body());
            return $decodedResponse->choices[0]->message->content;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function chatDalle(array $query)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
        ])->post(env('OPENAI_API_DALL_E_URL'), $query);
        $response = json_decode($response->body());
        return $response->data[0]->url;
    }
    public static function generateTeaserFromOpenAI($prompt, $language): \stdClass|null
    {
        $format = 'Retourne moi le résultat en '.$language.' du contenu au format JSON suivant : {
            "title": "Titre de l\'histoire",
            "content": "Teaser de l\'histoire",
            "illustration": "Génère le prompt Dalle pour illustrer le contenu du teaser en suivant les instructions suivantes :
                - Le prompt doit être une phrase qui décrit l\'illustration à générer
                - Le prompt doit être en anglais
                - Le prompt doit être court
                - Le prompt doit être pertinent par rapport au contenu du chapitre",
                - Le prompt doit être rédigé en suivant les règles de génération suivantes :
                     Dall-e Ai prompt requirements :
                     1. Subject of the prompt with lot of details (who, what, where, when)
                     2. Media style (photo, painting, drawing, manga, digital art, etc.)
                     3. The Style (comic, realistic, abstract, etc.)  and artist style (Picasso, Van Gogh, etc.)
                     4. Resolution (size of the image)
                     5. Mood (happy, sad, horror, etc.)
                     6. Color (black and white, color, etc.)
                     7. Shading (Three-point lighting, Butterfly Lightning, backlighting, studio lighting, etc.)
                     8. Angle of view (bird\'s eye view, worm\'s eye view, etc.)"
        }';
        $directive = 'Rédige un teaser de deux phrases qui donnent envie de lire l\'histoire qui sera générée à partir du texte suivant dans un thème horrifique et cauchemardesque :';
        if($prompt !== null && $prompt !== '') {
            $formattedPrompt = $directive . ' ' . $prompt . '. ' . $format;

            $queryBuilder = new OpenAiQueryBuilder();
            $query = $queryBuilder
                ->setModel('gpt-3.5-turbo')
                ->setRole('user')
                ->setContent($formattedPrompt)
                ->setTemperature(1)
                ->build();

            $response = self::chatGPT($query);
            if(!json_validate($response)) {
                $response = self::correctJsonFormat($response);
            }
            return json_decode($response);
        }
        return null;
    }

    public static function generateStoryFromOpenAI(string $prompt, string $title, string $teaserContent, string $language): \stdClass|null
    {
        $directive = 'Rédige une histoire en '. $language .' contenant 1 chapitre de au moins 1 paragraphe de 3 lignes minimum à partir du texte' . $prompt . ' et du teaser :' . $teaserContent . '.';
        $format = 'Retourne une réponse au format JSON suivant : {
            "title": '. $title .',
            "chapters": [
                {
                    "title": "titre du chapitre",
                    "number": "numéro de chapitre de type entier",
                    "illustration": "Génère le prompt Dalle pour illustrer le contenu du chapitre en suivant les instructions suivantes :
                        - Le prompt doit être une phrase qui décrit l\'illustration à générer
                        - Le prompt doit être en anglais
                        - Le prompt doit être court
                        - Le prompt doit être pertinent par rapport au contenu du chapitre",
                        - Le prompt doit être rédigé en suivant les règles de génération suivantes :
                             Dall-e Ai prompt requirements :
                             1. Subject of the prompt with lot of details (who, what, where, when)
                             2. Media style (photo, painting, drawing, manga, digital art, etc.)
                             3. The Style (comic, realistic, abstract, etc.)  and artist style (Picasso, Van Gogh, etc.)
                             4. Resolution (size of the image)
                             5. Mood (happy, sad, horror, etc.)
                             6. Color (black and white, color, etc.)
                             7. Shading (Three-point lighting, Butterfly Lightning, backlighting, studio lighting, etc.)
                             8. Angle of view (bird\'s eye view, worm\'s eye view, etc.)
                    "paragraphs": [
                        {
                            "content": "contenu du paragraphe",
                            "order": "numéro du paragraphe",
                            "chapter_number": "numéro de chapitre"
                        }
                    ]
                }
            ]
        }';

        if ($prompt !== '' && $teaserContent !== '' && $title !== '') {
            $formattedPrompt = $directive . '.' . $format;

            $queryBuilder = new OpenAiQueryBuilder();
            $query = $queryBuilder
                ->setModel('gpt-3.5-turbo')
                ->setRole('user')
                ->setContent($formattedPrompt)
                ->setTemperature(1)
                ->build();

            $response = self::chatGPT($query);
            return json_decode($response);
        }
        return null;
    }

    public static function generateIllustrationFromDalle(string $prompt): string|null
    {
        if ($prompt !== '') {
            $queryBuilder = new OpenAiDalleQueryBuilder();
            $query = $queryBuilder
                ->setModel('dall-e-3')
                ->setPrompt($prompt)
                ->setSize('1024x1024')
                ->build();
            return self::chatDalle($query);
        }
        return null;
    }

    public static function generateRandomPrompt(string $language): string|null
    {
        $directive = 'Rédige un prompt original et aléatoire dans le but de raconter une histoire d\'horreur, trash, satanique en '. $language . 'à partir du prompt généré.';
        $format = 'Retourne une réponse au format JSON suivant : {
            "prompt": "prompt généré"
        }';
        $formattedPrompt = $directive . '.'. $format;

        $queryBuilder = new OpenAiQueryBuilder();
        $query = $queryBuilder
            ->setModel('gpt-3.5-turbo')
            ->setRole('user')
            ->setContent($formattedPrompt)
            ->setTemperature(1)
            ->build();

        $response = self::chatGPT($query);
        return json_decode($response)->prompt;
    }

    public static function correctJsonFormat($jsonString): string
    {
        // Supprime les virgules à la fin d'un objet JSON
        $correctedJsonString = preg_replace('/,\s*(?=})/', '', $jsonString);

        // Vérifie si le JSON corrigé est valide
        json_decode($correctedJsonString);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $correctedJsonString;
        } else {
            // Si le JSON corrigé est toujours invalide, renvoie l'erreur
            return json_last_error_msg();
        }
    }
}
