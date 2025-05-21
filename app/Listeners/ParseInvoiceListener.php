<?php

namespace App\Listeners;

use App\Events\ParseInvoice;
use App\Services\DocumentAi;
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

            // TODO: prompt an LLM to structure response to JSON;
            // then to robocorp for journal entrying.
        } catch (\Exception $e) {
            Log::error('Error in parsing invoice: ' . $e->getMessage());
        }
    }
}
