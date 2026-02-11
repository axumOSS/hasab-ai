<?php

namespace Tests;

use Axumoss\HasabAi\ApiClient;
use Axumoss\HasabAi\Endpoints\Transcription;

it('uploads audio for transcription', function () {
    $client = $this->createMock(ApiClient::class);
    $audio = 'test.mp3';

    // We expect postMultipart to be called with the right fields
    $client->expects($this->once())
        ->method('postMultipart')
        ->with('upload-audio', $this->callback(function ($fields) {
            return $fields['language'] === 'amh' &&
                $fields['source_language'] === 'amh' &&
                $fields['translate'] === 'false' &&
                $fields['summarize'] === 'false' &&
                $fields['is_meeting'] === 'false';
        }))
        ->willReturn(['text' => 'transcribed text']);

    $transcription = new Transcription($client);
    $response = $transcription->upload($audio);

    expect($response)
        ->toBeArray()
        ->toHaveKey('text', 'transcribed text');
});

it('uploads audio with custom parameters', function () {
    $client = $this->createMock(ApiClient::class);
    $audio = 'test.mp3';

    $client->expects($this->once())
        ->method('postMultipart')
        ->with('upload-audio', $this->callback(function ($fields) {
            return $fields['language'] === 'eng' &&
                $fields['source_language'] === 'amh' &&
                $fields['translate'] === 'true' &&
                $fields['summarize'] === 'true' &&
                $fields['is_meeting'] === 'true';
        }))
        ->willReturn(['text' => 'translated and summarized text']);

    $transcription = new Transcription($client);
    $response = $transcription->upload(
        audio: $audio,
        language: 'eng',
        sourceLanguage: 'amh',
        translate: true,
        summarize: true,
        isMeeting: true
    );

    expect($response)->toBeArray();
});
