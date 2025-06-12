<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'transaction_id',
        'account_id',
        'tax',
        'tax_ledger_entry_id',
        'entry_type',
        'description',
        'amount',
    ];

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function ledgerAccounts(): BelongsTo
    {
        return $this->belongsTo(LedgerAccount::class);
    }
}
