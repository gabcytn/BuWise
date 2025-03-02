<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasUuids;
    protected $fillable = [
        'accountant_id',
        'email',
        'tin',
        'phone_number',
        'client_type',
        'name',
        'password',
        'profile_img',
    ];

    protected $hidden = [
        "password"
    ];

    protected function casts(): array
    {
        return [
            "password" => "hashed"
        ];
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
