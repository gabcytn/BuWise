<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public const APPROVED = 1;
    public const PENDING = 2;
    public const REJECTED = 3;

    protected $fillable = [
        'description'
    ];

    /*
     * @return HasMany
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
}
