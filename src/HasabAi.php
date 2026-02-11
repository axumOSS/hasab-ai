<?php

namespace Axumoss\HasabAi;

use Axumoss\HasabAi\Endpoints\Transcription;
use Axumoss\HasabAi\Endpoints\Translation;
use Axumoss\HasabAi\Endpoints\TextToSpeech;

class HasabAi
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function transcribe(): Transcription
    {
        return app(Transcription::class);
    }

    public function translate(): Translation
    {
        return app(Translation::class);
    }

    public function tts(): TextToSpeech
    {
        return app(TextToSpeech::class);
    }
}
