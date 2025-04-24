<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    public const ASSETS = 1;
    public const LIABILITIES = 2;
    public const EQUITY = 3;
    public const EXPENSES = 4;
    public const REVENUE = 5;

    protected $fillable = [
        'name',
    ];
}
