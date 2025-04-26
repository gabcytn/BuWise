<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, TwoFactorAuthenticatable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'accountant_id',
        'tin',
        'phone_number',
        'client_type',
        'password',
        'role_id',
        'profile_img',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /*
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(User::class, 'accountant_id')->where('role_id', Role::CLIENT);
    }

    /*
     * @return BelongsTo
     */
    public function staff(): HasMany
    {
        return $this
            ->hasMany(User::class, 'accountant_id')
            ->where(function ($query) {
                $query
                    ->where('role_id', Role::LIAISON)
                    ->orWhere('role_id', Role::CLERK);
            });
    }

    /*
     * @return BelongsTo
     */
    public function accountant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    /*
     * @return HasManyThrough
     */
    public function clientsJournalEntries()
    {
        return $this
            ->hasManyThrough(
                JournalEntry::class,
                User::class,
                'accountant_id',
                'client_id',
                'id',
                'id'
            )
            ->select('journal_entries.*')
            ->with('client');
    }

    /*
     * @return HasMany
     */
    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    /*
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /*
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function ledgerAccounts(): HasMany
    {
        return $this->hasMany(LedgerAccount::class);
    }
}
