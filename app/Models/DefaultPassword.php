<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultPassword extends Model
{
    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $primaryKey = 'user_id';
}
