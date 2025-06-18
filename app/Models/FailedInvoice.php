<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedInvoice extends Model
{
    protected $table = [
        'client_id',
        'filename',
    ];
}
