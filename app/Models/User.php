<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'email_verified_at', 'msmhs_id', 'activation_status', 'activation_requested_at', 'activated_at', 'activated_by', 'activation_rejected_at', 'activation_rejection_note'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'msmhs_id',
        'activation_status',
        'activation_requested_at',
        'activated_at',
        'activated_by',
        'activation_rejected_at',
        'activation_rejection_note',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Msmhs::class, 'msmhs_id');
    }

    public function skpiSubmissions(): HasMany
    {
        return $this->hasMany(SkpiSubmission::class);
    }

    public function activator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

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
            'activation_requested_at' => 'datetime',
            'activated_at' => 'datetime',
            'activation_rejected_at' => 'datetime',
        ];
    }
}
