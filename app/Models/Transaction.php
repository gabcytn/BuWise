<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'status',
        'type',
        'kind',
        'amount',
        'withholding_tax',
        'date',
        'payment_method',
        'description',
        'reference_no',
        'image',
    ];

    protected static function booted()
    {
        static::updating(function ($model) {
            $journal_id = $model->id;
            Cache::put("journal-$journal_id-old", $model->getOriginal(), 300);
        });
    }

    protected $dispatchesEvents = [
        'saved' => TransactionCreated::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ledger_entries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'invoice_id');
    }
}
