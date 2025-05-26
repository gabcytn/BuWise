<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'transaction_type_id',
        'entry_type_id',
        'tax_id',
        'amount',
    ];

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function journalEntries(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo;
     */
    public function ledgerAccounts(): BelongsTo
    {
        return $this->belongsTo(LedgerAccount::class);
    }
}
