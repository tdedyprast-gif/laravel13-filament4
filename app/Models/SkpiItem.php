<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkpiItem extends Model
{
    protected $fillable = [
        'skpi_submission_id',
        'category',
        'title',
        'organizer',
        'level',
        'achievement',
        'description',
        'started_on',
        'ended_on',
        'certificate_number',
        'certificate_file',
        'sort_order',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_note',
    ];

    protected function casts(): array
    {
        return [
            'started_on' => 'date',
            'ended_on' => 'date',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SkpiSubmission::class, 'skpi_submission_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
