<?php

namespace Axumoss\HasabAi;

use Illuminate\Support\ServiceProvider;
use Axumoss\HasabAi\Endpoints\Transcription;
use Axumoss\HasabAi\Endpoints\Translation;
use Axumoss\HasabAi\Endpoints\TextToSpeech;
use Axumoss\HasabAi\Endpoints\AIChat;

class HasabAiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hasabai.php', 'hasabai');

        // Register the API client
        $this->app->singleton(ApiClient::class, function ($app) {
            return new ApiClient();
        });

        // Register endpoints
        $this->app->singleton(Transcription::class, fn($app) => new Transcription($app->make(ApiClient::class)));
        $this->app->singleton(Translation::class, fn($app) => new Translation($app->make(ApiClient::class)));
        $this->app->singleton(TextToSpeech::class, fn($app) => new TextToSpeech($app->make(ApiClient::class)));
        $this->app->singleton(AIChat::class, fn($app) => new AIChat($app->make(ApiClient::class)));

        // Facade binding
        $this->app->singleton('hasabai', function ($app) {
            return (object)[
                'transcription' => $app->make(Transcription::class),
                'translation' => $app->make(Translation::class),
                'tts' => $app->make(TextToSpeech::class),
                'aiChat' => $app->make(AIChat::class),
            ];
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hasabai.php' => config_path('hasabai.php'),
        ], 'config');
    }
}
