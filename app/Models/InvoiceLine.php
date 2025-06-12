<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = [
        'invoice_id',
        'tax',
        'item_name',
        'quantity',
        'unit_price',
        'discount'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'invoice_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}
