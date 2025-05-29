<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    public const CASH = 1;
    public const INPUT_VAT_RECEIVABLE = 6;
    public const TAX_PAYABLE = 20;
    public const OUTPUT_VAT_PAYABLE = 21;
    public const SALES = 30;

    protected $fillable = [
        'id',
        'account_group_id',
        'client_id',
        'name',
    ];

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
