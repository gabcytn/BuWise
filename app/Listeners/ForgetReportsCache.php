<?php

namespace App\Listeners;

use App\Events\TransactionDeleted;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ForgetReportsCache implements ShouldQueue
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
    public function handle(TransactionDeleted $event): void
    {
        $start_year = Carbon::now()->startOfYear()->format('Y-m-d');
        $end_year = Carbon::now()->endOfYear()->format('Y-m-d');
        $date = Carbon::parse($event->date);
        if ($date->between($start_year, $end_year)) {
            $client_id = $event->client_id;
            Cache::forget("$client_id-balance-sheet");
            Cache::forget("$client_id-income-statement");
            Log::info('Successfully forgets reports cache');
        }
    }
}
