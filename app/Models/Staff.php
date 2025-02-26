<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = "staffs";
    protected $fillable = [
        "accountant_id",
        "staff_id",
    ];
}
