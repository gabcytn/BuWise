<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'client_id',
        'invoice_id',
        'transaction_type_id',
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
        return $this->belongsTo(User::class);
    }
}
