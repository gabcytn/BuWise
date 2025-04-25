<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    public const SALES = 1;
    public const PURCHASE = 2;

    public const LOOKUP = [
        'Sales' => 1,
        'Purchases' => 2,
    ];

    /*
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
