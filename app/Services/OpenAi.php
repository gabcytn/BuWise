<?php
namespace App\Services;

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
        $base_prompt = new Prompt()->getPrompt();
        $this->input = $base_prompt . $p;
    }

    public function prompt(): string
    {
        $url = env('OPEN_AI_URL');
        $postData = json_encode([
            'model' => env('OPEN_AI_MODEL'),
            'input' => $this->input
        ]);
        $ch = curl_init($url);
        try {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . env('OPEN_AI_KEY'),
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);

            if ($response === false) {
                throw new \Exception(curl_error($ch));
            }

            $data = json_decode($response, false);
            $text = $data->output[0]->content[0]->text;
        } catch (\Exception $e) {
            Log::error('Error prompting GPT: ' . $e->getMessage());
            $text = '';
        } finally {
            curl_close($ch);
            return $text;
        }
    }
}

?>
