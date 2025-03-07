<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const ACCOUNTANT = 1;
    public const LIAISON = 2;
    public const CLERK = 3;
    public const CLIENT = 4;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
