<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username', 'firstname', 'lastname', 'email', 'avatar', 'password', 'verify_token', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token', 'verify_token',
    ];

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function readMessages()
    {
        return $this->belongsToMany(Message::class, 'message_reads')
                    ->withTimestamps();
    }

    // Demandes d'ami envoyées par cet utilisateur
    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id')
            ->where('status', '!=', 'accepted');
    }

    // Demandes d'ami reçues par cet utilisateur
    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id')
            ->where('status', '!=', 'accepted');
    }
}
