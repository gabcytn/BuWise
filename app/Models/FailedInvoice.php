<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedInvoice extends Model
{
    protected $fillable = [
        'client_id',
        'filename',
    ];
}
