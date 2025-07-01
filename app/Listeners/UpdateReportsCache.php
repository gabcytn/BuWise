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
        try {
            $transaction = $event->transaction;
            $start_year = Carbon::now()->startOfYear();
            $end_year = Carbon::now()->endOfYear();
            $date = Carbon::parse($transaction->date);
            $client_id = $transaction->client_id;

            $old_data = Cache::get('journal-' . $transaction->id . '-old', null);
            // if model is updated, old date's in this year, and new date's not in this year
            if (
                $old_data &&
                Carbon::parse($old_data['date'])->between($start_year, $end_year) &&
                ($date->lessThan($start_year) || $date->greaterThan($end_year))
            ) {
                Log::info('Updated from this year to outside this year');
                $this->forgetCache($client_id, 'this_year');
                $this->forgetCache($client_id, 'this_quarter');
                Cache::forget('journal-' . $transaction->id . '-old');
                return;
            }

            if (!$date->between($start_year, $end_year)) {
                Log::info("Transaction created's date is not this year");
                return;
            }

            $start_quarter = Carbon::now()->startOfQuarter();
            $end_quarter = Carbon::now()->endOfQuarter();

            $this->forgetCache($client_id, 'this_year');

            if ($date->between($start_quarter, $end_quarter))
                $this->forgetCache($client_id, 'this_quarter');

            Log::info('Successfully forgets reports cache');
        } catch (\Exception $e) {
            Log::error('Error forgetting reports cache');
            Log::error($e->getMessage());
            Log::error(truncate($e->getTraceAsString(), 200));
        }
    }

    private function forgetCache(string $client_id, string $period)
    {
        Log::info("Forgetting $period");
        Cache::forget("$client_id-balance-sheet-$period");
        Cache::forget("$client_id-income-statement-$period");
        Cache::forget("api-$client_id-reports-$period");
    }
}
