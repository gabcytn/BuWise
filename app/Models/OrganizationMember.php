<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
    ];

    public $timestamps = false;
}
