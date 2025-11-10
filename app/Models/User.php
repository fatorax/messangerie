<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username', 'firstname', 'lastname', 'email', 'avatar', 'password', 'verify_token',
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
}
