<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class InvoiceCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceCreated $event): void
    {
        Log::info('RUNNING...');
        try {
            $url = env('RC_URL');
            $postData = json_encode([
                'image' => $event->invoiceUrl,
                'client_id' => $event->user->id,
                'invoice_id' => $event->invoiceId
            ]);

            $ch = curl_init($url);
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
