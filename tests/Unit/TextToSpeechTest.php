<?php

namespace Tests;

use Axumoss\HasabAi\ApiClient;
use Axumoss\HasabAi\Endpoints\TextToSpeech;

it('synthesizes speech', function () {
    $client = $this->createMock(ApiClient::class);
    $client->expects($this->once())
        ->method('request')
        ->with('POST', 'tts/synthesize', [
            'json' => ['text' => 'hello', 'language' => 'eng']
        ])
        ->willReturn(['audio_url' => 'http://example.com/audio.mp3']);

    $tts = new TextToSpeech($client);
    $response = $tts->synthesize('hello', 'eng');

    expect($response)
        ->toBeArray()
        ->toHaveKey('audio_url', 'http://example.com/audio.mp3');
});

it('synthesizes speech with speaker', function () {
    $client = $this->createMock(ApiClient::class);
    $client->expects($this->once())
        ->method('request')
        ->with('POST', 'tts/synthesize', [
            'json' => ['text' => 'hello', 'language' => 'eng', 'speaker_name' => 'male_1']
        ])
        ->willReturn(['audio_url' => 'http://example.com/audio.mp3']);

    $tts = new TextToSpeech($client);
    $response = $tts->synthesize('hello', 'eng', 'male_1');

    expect($response)->toBeArray();
});

it('gets available speakers', function () {
    $client = $this->createMock(ApiClient::class);
    $client->expects($this->once())
        ->method('request')
        ->with('GET', 'tts/speakers', ['query' => []])
        ->willReturn(['languages' => []]);

    $tts = new TextToSpeech($client);
    $response = $tts->getSpeakers();

    expect($response)->toBeArray();
});

it('gets available speakers with language filter', function () {
    $client = $this->createMock(ApiClient::class);
    $client->expects($this->once())
        ->method('request')
        ->with('GET', 'tts/speakers', ['query' => ['language' => 'eng']])
        ->willReturn(['languages' => []]);

    $tts = new TextToSpeech($client);
    $response = $tts->getSpeakers('eng');

    expect($response)->toBeArray();
});

it('gets history', function () {
    $client = $this->createMock(ApiClient::class);
    $params = ['limit' => 10];

    $client->expects($this->once())
        ->method('request')
        ->with('GET', 'tts/history', ['query' => $params])
        ->willReturn(['records' => []]);

    $tts = new TextToSpeech($client);
    $response = $tts->getHistory($params);

    expect($response)->toBeArray();
});

it('gets analytics', function () {
    $client = $this->createMock(ApiClient::class);
    $params = ['date_from' => '2023-01-01'];

    $client->expects($this->once())
        ->method('request')
        ->with('GET', 'tts/analytics', ['query' => $params])
        ->willReturn(['total_requests' => 10]);

    $tts = new TextToSpeech($client);
    $response = $tts->getAnalytics($params);

    expect($response)->toBeArray();
});

it('gets record', function () {
    $client = $this->createMock(ApiClient::class);
    $id = 123;

    $client->expects($this->once())
        ->method('request')
        ->with('GET', "tts/record/{$id}")
        ->willReturn(['id' => $id]);

    $tts = new TextToSpeech($client);
    $response = $tts->getRecord($id);

    expect($response)->toBeArray();
});

it('deletes record', function () {
    $client = $this->createMock(ApiClient::class);
    $id = 123;

    $client->expects($this->once())
        ->method('request')
        ->with('DELETE', "tts/record/{$id}")
        ->willReturn(['message' => 'deleted']);

    $tts = new TextToSpeech($client);
    $response = $tts->deleteRecord($id);

    expect($response)->toBeArray();
});

it('streams synthesis', function () {
    $client = $this->createMock(ApiClient::class);
    $client->expects($this->once())
        ->method('request')
        ->with('POST', 'tts/stream', [
            'json' => [
                'text' => 'hello',
                'language' => 'eng',
                'sample_rate' => 22050,
                'speaker_name' => 'default'
            ]
        ])
        ->willReturn(['session_id' => 'abc']);

    $tts = new TextToSpeech($client);
    $response = $tts->streamSynthesize('hello', 'eng');

    expect($response)->toBeArray();
});
