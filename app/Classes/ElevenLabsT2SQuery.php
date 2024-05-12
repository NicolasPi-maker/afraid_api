<?php

namespace App\Classes;

class ElevenLabsT2SQuery
{
    private string $model = 'eleven_multilingual_v2';

    private string $text = '';

    private array $voiceSettings = [
        'stability' => 0.5,
        'similarity_boost' => 0.5,
    ];

    public function __construct() {}

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setVoiceSettings(array $voiceSettings): void
    {
        $this->voiceSettings = $voiceSettings;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getVoiceSettings(): array
    {
        return $this->voiceSettings;
    }
}
