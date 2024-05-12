<?php

namespace App\Classes;

class OpenAIQuery
{
    private string $model = 'gpt-3.5-turbo';
    private array $messages = [];

    private string $role = 'user';

    private string $content = '';

    private int $max_tokens = 2000;

    private int $n = 1;

    private float $temperature = 0.5;

    public function __construct() {}

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    private function setMessages(): void
    {
        if($this->content === '') {
            throw new \Error('Content must be set before building the query');
        } else {
            $this->messages = [
                [
                    'role' => $this->role,
                    'content' => $this->content,
                ],
            ];
        }
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setMaxTokens(int $max_tokens): void
    {
        $this->max_tokens = $max_tokens;
    }

    public function setN(int $n): void
    {
        $this->n = $n;
    }

    public function setTemperature(float $temperature): void
    {
        if ($temperature < 0 || $temperature > 1) {
            throw new \Error('Temperature must be between 0 and 1');
        } else {
            $this->temperature = $temperature;
        }
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getMessages(): array
    {
        $this->setMessages();
        return $this->messages;
    }

    public function getMaxTokens(): int
    {
        return $this->max_tokens;
    }

    public function getN(): int
    {
        return $this->n;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }
}
