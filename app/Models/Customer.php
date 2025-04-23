<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'client_id',
        'name',
    ];

    /*
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
     * @return HasMany
     */
    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
