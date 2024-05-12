<?php

namespace App\Classes;

class OpenAiDalleQuery
{
    private string $model = 'dall-e-3';
    private string $prompt = '';
    private int $n = 1;
    private string $size = '1024x1024';

    private string $format = 'url';

    public function __construct() {}

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    public function setN(int $n): void
    {
        $this->n = $n;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function getN(): int
    {
        return $this->n;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
