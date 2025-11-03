<?php

namespace Axumoss\HasabAi;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class ApiClient
{
    protected string $baseUrl;
    protected string $token;

    public function __construct(string $token = null, string $baseUrl = null)
    {
        $this->token = $token ?? config('hasabai.token');
        $this->baseUrl = $baseUrl ?? config('hasabai.base_url');
    }

    /**
     * Send a request to the API.
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array $data POST/GET data
     * @param array $files Files to attach (['name' => fopen(...)])
     * @return array
     */
    public function request(string $method, string $endpoint, array $data = [], array $files = []): array
    {
        $request = Http::withToken($this->token);

        // Attach files if present
        foreach ($files as $name => $file) {
            $request = $request->attach($name, $file);
        }

        $response = $request->{$method}("{$this->baseUrl}/{$endpoint}", $data);

        if ($response->failed()) {
            throw new \Exception("API request failed: ".$response->body());
        }

        return $response->json();
    }
}
