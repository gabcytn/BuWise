<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'address',
        'logo'
    ];

    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }
}
