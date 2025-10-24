<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\InvoiceUploadProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendProcessedInvoiceNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new InvoiceUploadProcessed());
    }
}
