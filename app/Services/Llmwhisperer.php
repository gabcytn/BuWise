<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Llmwhisperer
{
    private string $url;

    public function __construct(
        private string $filename
    ) {
        $this->url = config('app.ocr_url');
    }

    public function extract(): string
    {
        $fileContents = Storage::disk('public')->get('temp/' . $this->filename);

        $response = Http::withHeaders([
            'unstract-key' => config('app.ocr_api_key'),
            'Content-Type' => 'application/octet-stream',
        ])
            ->withBody($fileContents)
            ->post($this->url . '/whisper?mode=form&output_mode=layout_preserving');

        if ($response->successful()) {
            $result = $response->json();
            $hash = $result['whisper_hash'];
            $output_text = $this->poll($hash);
            return $output_text;
        } else {
            $error = $response->body();
            Log::info('Error response');
            Log::info($error);
            return '';
        }
    }

    private function poll($hash)
    {
        while (true) {
            $response = Http::withHeaders([
                'unstract-key' => config('app.ocr_api_key'),
            ])
                ->get($this->url . "/whisper-status?whisper_hash=$hash");
            $body = $response->json();
            $status = $body['status'];
            Log::info($status);
            if ($status === 'error')
                return '';
            else if ($status === 'processed')
                break;
            else
                sleep(3);
        }

        return $this->retrieve($hash);
    }

    private function retrieve($hash)
    {
        $response = Http::withHeaders([
            'unstract-key' => config('app.ocr_api_key'),
        ])
            ->get($this->url . "/whisper-retrieve?whisper_hash=$hash");
        if (!$response->successful() || !$response->json()) {
            Log::error('/whisper-retrieve is not successful/no JSON');
            Log::error($response->body());
            return '';
        }

        $body = $response->json();
        if (!array_key_exists('result_text', $body)) {
            Log::error('response does not have a result_text key');
            return '';
        }

        $text = $body['result_text'];
        return $text;
    }
}
