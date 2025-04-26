<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public const PENDING = 1;
    public const VERIFIED = 2;
    public const REJECTED = 3;

    protected $fillable = [
        'description'
    ];

    /*
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
