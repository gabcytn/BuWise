<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'status',
        'type',
        'kind',
        'amount',
        'date',
        'payment_method',
        'description',
        'reference_no',
        'image',
    ];

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
