<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        'created_by',
        'tin',
        'phone_number',
        'client_type',
        'password',
        'gender',
        'role_id',
        'profile_img',
        'onboarded'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
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
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function failedInvoices(): HasManyThrough
    {
        return $this->hasManyThrough(FailedInvoice::class, User::class, 'accountant_id', 'client_id');
    }

    /*
     * @return Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function ledgerAccounts(): HasMany
    {
        return $this->hasMany(LedgerAccount::class);
    }

    public function organization(): HasOneThrough
    {
        return $this->hasOneThrough(
            Organization::class,
            OrganizationMember::class,
            'user_id',  // Foreign key on OrganizationMember table...
            'id',  // Foreign key on Organization table...
            'id',  // Local key on User table...
            'organization_id'  // Local key on OrganizationMember table...
        );
    }

    public function expoTokens(): HasMany
    {
        return $this->hasMany(ExpoToken::class, 'owner_id');
    }
}
