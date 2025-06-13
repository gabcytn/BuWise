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
        $this->url = env('LLMWHISPERER_URL');
    }

    public function extract()
    {
        $fileContents = Storage::disk('public')->get('temp/' . $this->filename);

        $response = Http::withHeaders([
            'unstract-key' => env('LLMWHISPERER_APIKEY'),
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
        }
    }

    private function poll($hash)
    {
        $is_done = false;
        while (!$is_done) {
            $response = Http::withHeaders([
                'unstract-key' => env('LLMWHISPERER_APIKEY'),
            ])
                ->get($this->url . "/whisper-status?whisper_hash=$hash");
            $body = $response->json();
            $is_done = $body['status'] === 'processed';
            if (!$is_done)
                sleep(3);
        }

        return $this->retrieve($hash);
    }

    private function retrieve($hash)
    {
        $response = Http::withHeaders([
            'unstract-key' => env('LLMWHISPERER_APIKEY'),
        ])
            ->get($this->url . "/whisper-retrieve?whisper_hash=$hash");
        if ($response->successful()) {
            $body = $response->json();
            $text = $body['result_text'];
            return $text;
        } else {
            Log::info($response->body());
            return '';
        }
    }
}
