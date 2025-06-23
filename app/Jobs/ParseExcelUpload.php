<?php

namespace App\Jobs;

use App\Imports\SalesImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ParseExcelUpload implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $filename,
        private string $clientId,
        private string $creatorId,
        private string $transactionType,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(
            new SalesImport($this->clientId, $this->creatorId, $this->transactionType),
            Storage::disk('public')->path('csv/' . $this->filename)
        );

        Storage::disk('public')->delete('csv/' . $this->filename);
    }
}
