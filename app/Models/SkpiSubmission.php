<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkpiSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'msmhs_id',
        'nomor_skpi',
        'status',
        'submitted_at',
        'verified_at',
        'verified_by',
        'rejected_at',
        'rejected_by',
        'rejection_note',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Msmhs::class, 'msmhs_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SkpiItem::class);
    }
}
