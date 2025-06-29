<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    public const CASH = 1;
    public const CHECKINGS = 2;
    public const SAVINGS = 3;
    public const PETTY_CASH = 4;
    public const ACCOUNTS_RECEIVABLE = 5;
    public const INPUT_VAT_RECEIVABLE = 6;
    public const ACCOUNTS_PAYABLE = 17;
    public const TAX_PAYABLE = 20;
    public const OUTPUT_VAT_PAYABLE = 21;
    public const SALES = 30;
    public const SALES_DISCOUNT = 32;
    public const GENERAL_EXPENSE = 36;

    public const TYPES = [
        'cash',
        'receivable',
        'payable',
    ];

    protected $fillable = [
        'code',
        'account_group_id',
        'type',
        'accountant_id',
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
