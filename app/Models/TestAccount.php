<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAccount extends Model
{
    protected $fillable = [
        'requester_email',
        'user1_id',
        'username1',
        'password1',
        'email1',
        'user2_id',
        'username2',
        'password2',
        'email2',
        'resend_count',
        'last_resend_at',
        'expires_at',
    ];

    protected $casts = [
        'last_resend_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Durée de vie des comptes de test en heures
     */
    public const LIFETIME_HOURS = 24;

    /**
     * Vérifie si les comptes de test ont expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }
}
