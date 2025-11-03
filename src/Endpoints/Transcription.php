<?php

namespace Axumoss\HasabAi\Endpoints;

use Axumoss\HasabAi\ApiClient;

class Transcription
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function transcribe(string $filePath, array $params = []): array
    {
        $files = [
            'file' => fopen($filePath, 'r')
        ];

        return $this->client->request('post', 'transcription', $params, $files);
    }
}
