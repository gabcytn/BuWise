<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\NewFailedInvoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScanFailed implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $user_to_notify
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user_to_notify->notify(new NewFailedInvoice($this->user_to_notify));
    }
}
