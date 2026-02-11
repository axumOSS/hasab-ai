<?php

namespace Axumoss\HasabAi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiClient
{
    protected string $baseUrl;
    protected string $token;
    protected Client $client;

    public function __construct(?string $token = null, ?string $baseUrl = null)
    {
        $this->token = $token ?? config('hasabai.token');
        $this->baseUrl = rtrim(trim($baseUrl ?? config('hasabai.base_url'), "'\""), '/');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a POST request with multipart/form-data
     *
     * @param string $endpoint
     * @param array $fields Key/value pairs (arrays will be JSON-encoded)
     * @return array
     * @throws \Exception
     */
    public function postMultipart(string $endpoint, array $fields): array
    {
        $multipart = [];

        foreach ($fields as $name => $value) {
            $multipart[] = [
                'name' => $name,
                'contents' => is_array($value) ? json_encode($value) : $value,
            ];
        }

        try {
            $response = $this->client->request('POST', $endpoint, [
                'multipart' => $multipart,
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            return $decoded ?? ($body ? ['_content' => $body] : []);
        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            throw new \Exception("API request failed: {$message}");
        }
    }

    /**
     * Generic request (GET, POST, etc.) if needed
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->request(strtoupper($method), $endpoint, $options);
            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            return $decoded ?? ($body ? ['_content' => $body] : []);
        } catch (RequestException $e) {
            $message = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();
            throw new \Exception("API request failed: {$message}");
        }
    }
}
