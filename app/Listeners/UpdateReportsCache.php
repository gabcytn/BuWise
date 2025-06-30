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
        try {
            $transaction = $event->transaction;
            $start_year = Carbon::now()->startOfYear()->format('Y-m-d');
            $end_year = Carbon::now()->endOfYear()->format('Y-m-d');
            $date = Carbon::parse($transaction->date);

            if (!$date->between($start_year, $end_year))
                return;

            $start_quarter = Carbon::now()->startOfQuarter();
            $end_quarter = Carbon::now()->endOfQuarter();

            $client_id = $transaction->client_id;
            Log::info('Forgetting this year');
            Cache::forget("$client_id-balance-sheet-this_year");
            Cache::forget("$client_id-income-statement-this_year");

            if ($date->between($start_quarter, $end_quarter)) {
                Log::info('Forgetting this quarter');
                Cache::forget("$client_id-balance-sheet-this_quarter");
                Cache::forget("$client_id-income-statement-this_quarter");
            }

            Log::info('Successfully forgets reports cache');
        } catch (\Exception $e) {
            Log::error('Error forgetting reports cache');
            Log::error($e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 200));
        }
    }
}
