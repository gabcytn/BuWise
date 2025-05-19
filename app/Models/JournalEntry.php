<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'client_id',
        'invoice_id',
        'transaction_type_id',
        'status_id',
        'description',
        'date'
    ];

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
