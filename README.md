# Hasab-AI Laravel SDK

A Laravel SDK for interacting with the Hasab-AI API, providing easy access to Translation, Transcription, and Text-to-Speech (TTS) services.

## Installation

You can install the package via composer:

```bash
composer require axumoss/hasab-ai
```

The service provider will automatically register itself.

## Configuration

You can publish the config file using:

```bash
php artisan vendor:publish --provider="Axumoss\HasabAi\HasabAiServiceProvider" --tag="config"
```

Then, add your Hasab-AI API token to your `.env` file:

```env
HASABAI_TOKEN=your_api_token_here
```

## Usage

### Using the Facade

The `HasabAi` facade provides access to all services:

```php
use Axumoss\HasabAi\Facades\HasabAi;

// Transcription
$result = HasabAi::transcribe()->upload('path/to/audio.mp3', 'amh');

// Translation
$result = HasabAi::translate()->translate(['Hello', 'World'], 'eng', 'orm');

// Text-to-Speech
$result = HasabAi::tts()->synthesize('ሰላም', 'amh');
```

### Using Service Injection

You can also inject the specific endpoint classes:

```php
use Axumoss\HasabAi\Endpoints\Transcription;

public function transcribe(Transcription $transcription)
{
    return $transcription->upload('path/to/audio.mp3');
}
```

### Endpoints

#### Transcription
- `upload($audio, string $language = 'amh', ?string $sourceLanguage = 'amh', bool $translate = false, bool $summarize = false, bool $isMeeting = false)`
- `history(int $page = 1)`

#### Translation
- `translate(array $texts, string $sourceLanguage, string $targetLanguage)`

#### Text-to-Speech (TTS)
- `synthesize(string $text, string $language, ?string $speakerName = null)`
- `getSpeakers(?string $language = null)`
- `getHistory(array $params = [])`
- `getAnalytics(array $params = [])`
- `getRecord($recordId)`
- `deleteRecord($recordId)`
- `streamSynthesize(string $text, string $language, ?string $speakerName = null, int $sampleRate = 22050)`

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
