<?php

declare(strict_types=1);

/**
 * Build a 1,000-word Spanish vocabulary JSON file for BloomVocab.
 *
 * Requirements: PHP 8.1+
 * Optional: espeak-ng or espeak for IPA pronunciation.
 * Optional: OPENAI_API_KEY environment variable for part-of-speech labels.
 *
 * Run: php build_spanish_vocab.php
 */

const SOURCE_URL = 'https://raw.githubusercontent.com/exekis/lang-1000/main/src/data/spanish/words.json';

const SOURCE_CACHE = __DIR__ . '/spanish_1000_source.json';
const OUTPUT_FILE = __DIR__ . '/spanish_vocabulary_1000.json';

function downloadSource(): array
{
    if (is_file(SOURCE_CACHE)) {
        $cached = json_decode(
            file_get_contents(SOURCE_CACHE),
            true
        );

        if (is_array($cached) && count($cached) >= 1000) {
            return array_slice($cached, 0, 1000);
        }
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 45,
            'header' => "User-Agent: BloomVocab-Builder/1.0\r\n",
        ],
    ]);

    $json = file_get_contents(SOURCE_URL, false, $context);

    if ($json === false) {
        throw new RuntimeException(
            'Could not download the source dataset. Check your internet connection.'
        );
    }

    file_put_contents(SOURCE_CACHE, $json);

    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

    if (!is_array($data) || count($data) < 1000) {
        throw new RuntimeException(
            'The source did not contain at least 1,000 entries.'
        );
    }

    return array_slice($data, 0, 1000);
}

function getPronunciation(string $word): string
{
    $binary = trim((string) shell_exec('command -v espeak-ng 2>/dev/null'));

    if ($binary === '') {
        $binary = trim((string) shell_exec('command -v espeak 2>/dev/null'));
    }

    if ($binary === '') {
        return '';
    }

    $command = escapeshellarg($binary)
        . ' -q --ipa=3 -v fr-fr '
        . escapeshellarg($word)
        . ' 2>/dev/null';

    return trim((string) shell_exec($command));
}

function classifyPartsOfSpeech(array $words): array
{
    $apiKey = getenv('OPENAI_API_KEY');

    if (!$apiKey) {
        return array_fill(0, count($words), 'unknown');
    }

    $model = getenv('OPENAI_MODEL') ?: 'gpt-4o-mini';
    $labels = [];

    foreach (array_chunk($words, 100) as $batch) {
        $prompt = 'Classify each French word or phrase by its most likely '
            . 'part of speech. Use concise English labels such as noun, verb, '
            . 'adjective, adverb, pronoun, preposition, article, conjunction, '
            . 'interjection, or phrase. Return only a JSON object with an '
            . '"items" array containing one label per input, in the same order. '
            . 'Words: ' . json_encode($batch, JSON_UNESCAPED_UNICODE);

        $payload = json_encode([
            'model' => $model,
            'temperature' => 0,
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You accurately classify French vocabulary.',
                ],
                ['role' => 'user', 'content' => $prompt],
            ],
        ], JSON_UNESCAPED_UNICODE);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'timeout' => 60,
                'header' => [
                    "Authorization: Bearer {$apiKey}",
                    'Content-Type: application/json',
                ],
                'content' => $payload,
                'ignore_errors' => true,
            ],
        ]);

        $response = file_get_contents(
            'https://api.openai.com/v1/chat/completions',
            false,
            $context
        );

        if ($response === false) {
            throw new RuntimeException('The POS API request failed.');
        }

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new RuntimeException(
                $result['error']['message'] ?? 'OpenAI API error.'
            );
        }

        $content = $result['choices'][0]['message']['content'] ?? '';
        $decoded = json_decode($content, true);
        $batchLabels = $decoded['items'] ?? [];

        if (count($batchLabels) !== count($batch)) {
            throw new RuntimeException(
                'The POS API returned an unexpected number of labels.'
            );
        }

        foreach ($batchLabels as $label) {
            $labels[] = strtolower(trim((string) $label));
        }

        echo 'Classified ' . count($labels) . '/' . count($words) . " words\n";
    }

    return $labels;
}

try {
    echo "Downloading Spanish vocabulary...\n";

    $source = downloadSource();
    $records = [];

    foreach ($source as $item) {
        $word = trim((string) ($item['spanish'] ?? ''));
        $translation = trim((string) ($item['english'] ?? ''));
        $example = trim((string) ($item['exampleSpanish'] ?? ''));

        if ($word === '' || $translation === '' || $example === '') {
            throw new RuntimeException(
                'A source entry is missing a word, translation, or example. '
                . 'Inspect spanish_1000_source.json.'
            );
        }

        $records[] = [
            'language' => 'Spanish',
            'word' => $word,
            'translation' => $translation,
            'pronunciation' => getPronunciation($word),
            'part_of_speech' => 'unknown',
            'example_sentence' => $example,
        ];
    }

    $apiKey = getenv('OPENAI_API_KEY');

    if ($apiKey) {
        $words = array_column($records, 'word');
        $partsOfSpeech = classifyPartsOfSpeech($words);

        foreach ($records as $index => &$record) {
            $record['part_of_speech'] = $partsOfSpeech[$index];
        }
        unset($record);
    } else {
        echo "OPENAI_API_KEY not set; part_of_speech will be 'unknown'.\n";
    }

    file_put_contents(
        OUTPUT_FILE,
        json_encode(
            $records,
            JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
        ) . PHP_EOL
    );

    echo 'Success! Created ' . OUTPUT_FILE . PHP_EOL;
    echo 'Entries: ' . count($records) . PHP_EOL;

} catch (Throwable $exception) {
    fwrite(STDERR, 'Error: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
