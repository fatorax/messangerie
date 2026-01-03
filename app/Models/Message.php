<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\MessageRead;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_id', 'user_id', 'content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readers()
    {
        return $this->belongsToMany(User::class, 'message_reads')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    /**
     * Vérifie si le message a été lu par un utilisateur
     */
    public function isReadBy($userId): bool
    {
        return $this->readers()->where('user_id', $userId)->exists();
    }

    /**
     * Vérifie si le message a été lu par au moins une personne (autre que l'expéditeur)
     */
    public function isRead(): bool
    {
        return $this->readers()->where('user_id', '!=', $this->user_id)->exists();
    }

    /**
     * Marque le message comme lu par un utilisateur et notifie l'expéditeur
     */
    public function markAsReadBy($userId): void
    {
        if (!$this->isReadBy($userId) && $userId !== $this->user_id) {
            $this->readers()->attach($userId, ['read_at' => now()]);
            
            // Notifier l'expéditeur que son message a été lu
            event(new MessageRead($this, $userId));
        }
    }
}
