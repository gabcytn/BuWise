<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\MissingInvoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchMissingInvoiceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $creator_id,
        private string $client_id,
        private array $invoice_numbers,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $invoice_numbers = $this->invoice_numbers;
        $missing = [];
        for ($i = 0; $i < count($invoice_numbers) - 1; $i++) {
            $current = $invoice_numbers[$i];
            $next = $invoice_numbers[$i + 1];
            $find = $current + 1;
            while ($find < $next && $i < count($invoice_numbers) - 1) {
                $missing[] = $find;
                $find++;
            }
        }
        if (count($missing) === 0)
            return;

        $accountant = User::find($this->creator_id);
        $client = User::find($this->client_id);

        if ($accountant) {
            $accountant->notify(new MissingInvoice($accountant, $missing));
        }

        // TODO: broadcast notifications on client-side JS
        if ($client) {
            // $client->notify(new MissingInvoice());
        }
    }
}
