<?php

namespace App\Listeners;

use App\Events\ParseInvoice;
use App\Services\DocumentAi;
use App\Services\OpenAi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ParseInvoiceListener implements ShouldQueue
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
    public function handle(ParseInvoice $event): void
    {
        try {
            $docAi = new DocumentAi($event->filename, $event->mimeType);
            $text = $docAi->parse();
            Log::info('Document Text: ' . $text);
            if ($text === '') {
                throw new \Exception('Document AI API error');
            }

            $openAi = new OpenAi($text);
            $response = $openAi->prompt();
            $payload = json_decode($response);
            $payload->invoiceId = $event->invoiceId;
            $payload->transactionType = $event->transactionType;
            Log::info('Payload: ' . json_encode($payload));

            // TODO: submit request to robocorp for journal entrying.
        } catch (\Exception $e) {
            Log::error('Error in parsing invoice: ' . $e->getMessage());
        }
    }
}
