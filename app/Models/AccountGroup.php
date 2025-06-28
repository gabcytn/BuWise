<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    public const ASSETS = 1;
    public const LIABILITIES = 2;
    public const EQUITY = 3;
    public const REVENUE = 4;
    public const EXPENSES = 5;

    public const LOOKUP = [
        'asset' => 1,
        'liability' => 2,
        'equity' => 3,
        'revenue' => 4,
        'expense' => 5,
    ];

    public const IS_TEMPORARY = [
        AccountGroup::ASSETS => false,
        AccountGroup::LIABILITIES => false,
        AccountGroup::EQUITY => false,
        AccountGroup::REVENUE => true,
        AccountGroup::EXPENSES => true,
    ];

    public const IS_PERMANENT = [
        AccountGroup::ASSETS => true,
        AccountGroup::LIABILITIES => true,
        AccountGroup::EQUITY => true,
        AccountGroup::REVENUE => false,
        AccountGroup::EXPENSES => false,
    ];

    protected $fillable = [
        'name',
    ];
}
