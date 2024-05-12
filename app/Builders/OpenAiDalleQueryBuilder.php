<?php

namespace App\Builders;

use App\Classes\OpenAiDalleQuery;

class OpenAiDalleQueryBuilder
{
    private OpenAiDalleQuery $query;

    public function __construct()
    {
        $this->query = new OpenAiDalleQuery();
    }

    public function setModel(string $model): self
    {
        $this->query->setModel($model);
        return $this;
    }

    public function setPrompt(string $prompt): self
    {
        $this->query->setPrompt($prompt);
        return $this;
    }

    public function setN(int $n): self
    {
        $this->query->setN($n);
        return $this;
    }

    public function setSize(string $size): self
    {
        $this->query->setSize($size);
        return $this;
    }

    public function setFormat(string $format): self
    {
        $this->query->setFormat($format);
        return $this;
    }

    public function resetQuery(): self
    {
        $this->query = new OpenAiDalleQuery();
        return $this;
    }

    public function build(): array
    {
        $query = [
            'model' => $this->query->getModel(),
            'prompt' => $this->query->getPrompt(),
            'n' => $this->query->getN(),
            'size' => $this->query->getSize(),
            'response_format' => $this->query->getFormat(),
        ];
        $this->resetQuery();
        return $query;
    }
}
