<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
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
