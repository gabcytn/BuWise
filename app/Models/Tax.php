<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'accountant_id',
        'name',
        'description'
    ];
}
