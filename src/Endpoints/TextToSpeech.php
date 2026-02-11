<?php

namespace Axumoss\HasabAi\Endpoints;

use Axumoss\HasabAi\ApiClient;

class TextToSpeech
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Convert text into speech audio.
     *
     * @param string $text The text to convert.
     * @param string $language Language code (e.g., 'amh', 'orm', 'tir', 'eng').
     * @param string|null $speakerName Optional speaker name.
     * @return array Response containing audio file data.
     * @throws \Exception
     */
    protected function _synthesize(string $text, string $language, ?string $speakerName = null): array
    {
        $json = [
            'text' => $text,
            'language' => $language,
        ];

        if ($speakerName) {
            $json['speaker_name'] = $speakerName;
        }

        return $this->client->request('POST', 'tts/synthesize', [
            'json' => $json,
        ]);
    }

    /**
     * Retrieve list of available speakers.
     *
     * @param string|null $language Optional language code to filter by.
     * @return array List of speakers and structure.
     * @throws \Exception
     */
    protected function _getSpeakers(?string $language = null): array
    {
        $query = [];
        if ($language) {
            $query['language'] = $language;
        }

        return $this->client->request('GET', 'tts/speakers', [
            'query' => $query,
        ]);
    }

    /**
     * Get user's TTS synthesis history.
     *
     * @param array $params Optional filters: limit, offset, status, tts_type, language, date_from, date_to, device_id.
     * @return array History records.
     * @throws \Exception
     */
    protected function _getHistory(array $params = []): array
    {
        return $this->client->request('GET', 'tts/history', [
            'query' => $params,
        ]);
    }

    /**
     * Get TTS usage statistics.
     *
     * @param array $params Optional filters: date_from, date_to.
     * @return array Usage statistics.
     * @throws \Exception
     */
    protected function _getAnalytics(array $params = []): array
    {
        return $this->client->request('GET', 'tts/analytics', [
            'query' => $params,
        ]);
    }

    /**
     * Get details of a specific TTS record.
     *
     * @param int|string $recordId
     * @return array Record details.
     * @throws \Exception
     */
    protected function _getRecord($recordId): array
    {
        return $this->client->request('GET', "tts/record/{$recordId}");
    }

    /**
     * Delete a TTS record and its audio file.
     *
     * @param int|string $recordId
     * @return array deletion confirmation.
     * @throws \Exception
     */
    protected function _deleteRecord($recordId): array
    {
        return $this->client->request('DELETE', "tts/record/{$recordId}");
    }

    /**
     * Start streaming TTS synthesis.
     *
     * @param string $text The text to convert.
     * @param string $language Language code.
     * @param string|null $speakerName Optional speaker name.
     * @param int $sampleRate Sample rate (8000-48000).
     * @return array Session ID and status.
     * @throws \Exception
     */
    protected function _streamSynthesize(string $text, string $language, ?string $speakerName = null, int $sampleRate = 22050): array
    {
        $json = [
            'text' => $text,
            'language' => $language,
            'sample_rate' => $sampleRate,
            'speaker_name' => $speakerName ?? 'default', // Spec says optional, but example shows "default".
        ];

        return $this->client->request('POST', 'tts/stream', [
            'json' => $json,
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
