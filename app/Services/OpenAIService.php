<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function chat(array $messages, string $model = 'gpt-3.5-turbo')
    {
        $response = $this->client->chat()->create([
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        return $response->choices[0]->message->content;
    }

    public function createEmbedding(string $text, string $model = 'text-embedding-ada-002')
    {
        $response = $this->client->embeddings()->create([
            'model' => $model,
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }
}