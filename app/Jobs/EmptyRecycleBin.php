<?php

namespace App\Jobs;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class EmptyRecycleBin implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transactions = Transaction::withTrashed()->get();
        foreach ($transactions as $transaction) {
            $deletion_date = Carbon::parse($transaction->deleted_at)->addDays(30);
            if (!$deletion_date->isToday())
                continue;

            if ($transaction->image)
                Storage::delete('invoices/' . $transaction->image);

            $transaction->forceDelete();
        }
    }
}
