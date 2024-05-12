<?php

namespace App\Builders;
use App\Classes\OpenAIQuery;

class OpenAiQueryBuilder
{
    private OpenAIQuery $query;

    public function __construct()
    {
        $this->query = new OpenAIQuery();
    }
    public function setModel(string $model): self
    {
        $this->query->setModel($model);
        return $this;
    }

    public function setRole(string $role): self
    {
        $this->query->setRole($role);
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->query->setContent($content);
        return $this;
    }

    public function setMaxTokens(int $max_tokens): self
    {
        $this->query->setMaxTokens($max_tokens);
        return $this;
    }

    public function setN(int $n): self
    {
        $this->query->setN($n);
        return $this;
    }

    public function setTemperature(float $temperature): self
    {
        $this->query->setTemperature($temperature);
        return $this;
    }

    public function resetQuery(): self
    {
        $this->query = new OpenAIQuery();
        return $this;
    }

    public function build(): array
    {
        $query = [
            'model' => $this->query->getModel(),
            'messages' => $this->query->getMessages(),
            'max_tokens' => $this->query->getMaxTokens(),
            'n' => $this->query->getN(),
            'temperature' => $this->query->getTemperature(),
        ];
        $this->resetQuery();
        return $query;
    }
}


