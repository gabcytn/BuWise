<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoiceReceived implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $filename,
        private string $transactionType,
        private User $user
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Parsing invoice...');
        try {
            $file = Storage::disk('public')->get("invoices/$this->filename");
            Storage::put("invoices/$this->filename", $file);

            $temp_url = Storage::temporaryUrl("invoices/$this->filename", now()->addMinutes(10));

            $postData = json_encode([
                'filename' => $this->filename,
                'image' => $temp_url,
                'transactionType' => $this->transactionType,
                'clientId' => $this->user->id,
            ]);

            $ch = curl_init(env('RC_URL'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: RC-WSKEY ' . env('RC_API_KEY')
            ]);

            $response = curl_exec($ch);

            if ($response === false) {
                Log::info('Failed request: ' . curl_error($ch));
            } else {
                $data = json_decode($response, true);
                Log::info('Successful request: ' . json_encode($data));
            }

            curl_close($ch);
        } catch (\Exception $e) {
            Log::alert('Error in invoice listener: ' . $e->getMessage());
        }
    }
}
