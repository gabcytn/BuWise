<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TriggerRobocorpBot implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $data
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Triggering robocorp bot..');
        Log::info('JSON Payload: ' . json_encode($this->data));
        try {
            $res = Http::withHeader('Authorization', 'RC-WSKEY ' . config('app.rc_api_key'))
                ->post(config('app.rc_url'), $this->data);

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
            Log::alert(truncate($e->getTraceAsString(), 200));
        }
    }
}
