<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status', 'conversation_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relation avec l'utilisateur qui envoie la demande
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relation avec l'utilisateur qui reçoit la demande
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
