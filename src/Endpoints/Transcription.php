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

    /**
     * Upload an audio file and get its transcription text.
     *
     * @param string|resource $audio Audio file path or open file resource.
     * @param string $language Target language code (default: "amh").
     * @param string|null $sourceLanguage Source language code (e.g., "amh").
     * @param bool $translate Whether to translate (default: false).
     * @param bool $summarize Whether to summarize (default: false).
     * @param bool $isMeeting Whether it is a meeting (default: false).
     * @return array Response.
     * @throws \Exception
     */
    protected function _upload($audio, string $language = 'amh', ?string $sourceLanguage = 'amh', bool $translate = false, bool $summarize = false, bool $isMeeting = false): array
    {
        $fields = [
            'language' => $language,
            'source_language' => $sourceLanguage ?? $language,
            'translate' => $translate ? 'true' : 'false',
            'summarize' => $summarize ? 'true' : 'false',
            'is_meeting' => $isMeeting ? 'true' : 'false',
        ];

        // Handle file
        if (is_string($audio) && file_exists($audio)) {
            $fields['audio'] = fopen($audio, 'r');
        } elseif (is_resource($audio)) {
            $fields['audio'] = $audio;
        } else {
            $fields['audio'] = $audio;
        }

        return $this->client->postMultipart('upload-audio', $fields);
    }

    /**
     * Retrieve a list of all transcription jobs.
     *
     * @param int $page Page number (default: 1).
     * @return array History response.
     * @throws \Exception
     */
    protected function _history(int $page = 1): array
    {
        return $this->client->request('GET', 'audios', [
            'query' => ['page' => $page],
        ]);
    }

    /**
     * Handle static method calls and delegate to the instance from the container.
     */
    public static function __callStatic($method, $args)
    {
        $methodName = '_' . $method;
        return app(static::class)->$methodName(...$args);
    }

    /**
     * Handle instance method calls.
     */
    public function __call($method, $args)
    {
        $methodName = '_' . $method;
        return $this->$methodName(...$args);
    }
}
