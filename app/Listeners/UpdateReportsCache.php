<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateReportsCache implements ShouldQueue
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
    public function handle(TransactionCreated $event): void
    {
        // TODO: re-update the cache instead of forgetting
        $transaction = $event->transaction;
        $start_year = Carbon::now()->startOfYear()->format('Y-m-d');
        $end_year = Carbon::now()->endOfYear()->format('Y-m-d');
        $date = Carbon::parse($transaction->date);
        if ($date->between($start_year, $end_year)) {
            $client_id = $transaction->client_id;
            Cache::forget("$client_id-balance-sheet");
            Cache::forget("$client_id-income-statement");
            Log::info('Successfully forgets reports cache');
        }
    }
}
