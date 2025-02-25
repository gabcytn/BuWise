<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $fillable = [
        'bookkeeper_id',
        'email',
        'client_type',
        'name',
        'password',
        'profile_img',
    ];

    public function bookkeeper(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
