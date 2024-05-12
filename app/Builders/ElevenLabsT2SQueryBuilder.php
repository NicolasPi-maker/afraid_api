<?php

namespace App\Builders;

use App\Classes\ElevenLabsT2SQuery;

class ElevenLabsT2SQueryBuilder
{
    private ElevenLabsT2SQuery $query;

    public function __construct()
    {
        $this->query = new ElevenLabsT2SQuery();
    }

    public function setModel(string $model): self
    {
        $this->query->setModel($model);
        return $this;
    }

    public function setText(string $text): self
    {
        $this->query->setText($text);
        return $this;
    }

    public function setVoiceSettings(array $voiceSettings): self
    {
        $this->query->setVoiceSettings($voiceSettings);
        return $this;
    }

    public function resetQuery(): self
    {
        $this->query = new ElevenLabsT2SQuery();
        return $this;
    }

    public function build(): array
    {
        $query = [
            'model_id' => $this->query->getModel(),
            'text' => $this->query->getText(),
            'voice_settings' => $this->query->getVoiceSettings(),
        ];
        $this->resetQuery();
        return $query;
    }

}
