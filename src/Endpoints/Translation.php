<?php

namespace Axumoss\HasabAi\Endpoints;

use Axumoss\HasabAi\ApiClient;

class Translation
{
    protected ApiClient $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Translate an array of texts.
     *
     * @param array $texts List of strings to translate
     * @param string $sourceLanguage ISO code of source language (e.g., 'eng')
     * @param string $targetLanguage ISO code of target language (e.g., 'orm')
     * @return array
     * @throws \Exception
     */
    protected function _translate(array $texts, string $sourceLanguage, string $targetLanguage): array
    {
        // Prepare fields for multipart
        $fields = [
            'text' => $texts,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
        ];

        // Call ApiClient
        return $this->client->postMultipart('translate', $fields);
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
