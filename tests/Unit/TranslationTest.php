<?php

namespace Tests;

use Axumoss\HasabAi\ApiClient;
use Axumoss\HasabAi\Endpoints\Translation;

it('translates texts', function () {
    $client = $this->createMock(ApiClient::class);
    $texts = ['Hello world'];

    $client->expects($this->once())
        ->method('postMultipart')
        ->with('translate', $this->callback(function ($fields) use ($texts) {
            return $fields['text'] === $texts &&
                $fields['source_language'] === 'eng' &&
                $fields['target_language'] === 'orm';
        }))
        ->willReturn(['status' => 'success', 'data' => ['translation' => ['translated_text' => json_encode(['ሰላም አለም'])]]]);

    $translation = new Translation($client);
    $response = $translation->translate($texts, 'eng', 'orm');

    expect($response['status'])->toBe('success');
});
