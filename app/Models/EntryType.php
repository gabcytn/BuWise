<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryType extends Model
{
    public const DEBIT = 1;
    public const CREDIT = 2;

    public const LOOKUP = [
        'debit' => 1,
        'credit' => 2,
    ];

    protected $fillable = [
        'name'
    ];
}
