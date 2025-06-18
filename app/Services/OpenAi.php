<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAi
{
    private string $input;

    public function __construct(
        public string $extractedText
    ) {
        $p = <<<EOD
                Raw OCR text:
                \"\"\"
                $extractedText
                \"\"\"
            EOD;
        $prompt = new Prompt();
        $base_prompt = $prompt->getPrompt();
        $this->input = $base_prompt . $p;
    }

    public function prompt(): string
    {
        $url = env('OPEN_AI_URL');
        $postData = [
            'model' => env('OPEN_AI_MODEL'),
            'input' => $this->input
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPEN_AI_KEY'),
            'Content-Type' => 'application/json'
        ])->post($url, $postData);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['output'][0]['content'][0]['text'];
            return $text;
        } else {
            Log::error('error in openai');
            Log::error($response->body());
            return 'error';
        }
    }
}

?>
