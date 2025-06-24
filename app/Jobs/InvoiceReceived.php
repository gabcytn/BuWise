<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
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
            $image_url = url('storage/temp/' . $this->filename);
            $postData = [
                'filename' => $this->filename,
                'image' => $image_url,
                'transactionType' => $this->transactionType,
                'clientId' => $this->user->id,
            ];

            $res = Http::withHeader('Authorization', 'RC-WSKEY ' . config('app.rc_api_key'))
                ->post(config('app.rc_url'), $postData);

            if ($res->failed()) {
                $res->throw();
            } else {
                Log::info('Successful request: ' . $res->body());
            }
        } catch (RequestException $e) {
            Log::alert('Request to RC Control Room Failed: ' . $e->getMessage());
            Log::alert(truncate($e->getTraceAsString(), 200));
        } catch (\Exception $e) {
            Log::alert('Error in invoice listener: ' . $e->getMessage());
        }
    }
}
