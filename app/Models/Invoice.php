<?php

namespace App\Models;

use App\Events\InvoiceCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'image',
        'amount',
        'issue_date',
        'due_date',
        'transaction_type_id',
        'invoice_number',
        'supplier',
        'vendor',
        'payment_method',
        'tax_id',
        'discount_type',
        'is_paid'
    ];

    /*
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /*
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /*
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /*
     * @return HasOne
     */
    public function journalEntry(): HasOne
    {
        return $this->hasOne(JournalEntry::class);
    }

    public function invoice_lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }
}
