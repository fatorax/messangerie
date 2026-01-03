<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\FriendRequest;

// ============================================
// Tests du modèle User
// ============================================

test('user has correct fillable attributes', function () {
    $user = new User();
    
    expect($user->getFillable())->toBe([
        'username', 'firstname', 'lastname', 'email', 'avatar', 'password', 'verify_token', 'role',
    ]);
});

test('user has correct hidden attributes', function () {
    $user = new User();
    
    expect($user->getHidden())->toBe([
        'password', 'remember_token', 'verify_token',
    ]);
});

test('user default role is user', function () {
    $user = User::factory()->make();
    
    expect($user->role)->toBe('user');
});

test('user can be admin', function () {
    $user = User::factory()->admin()->make();
    
    expect($user->role)->toBe('admin');
});

test('user can be demo', function () {
    $user = User::factory()->demo()->make();
    
    expect($user->role)->toBe('demo');
});

// ============================================
// Tests du modèle Conversation
// ============================================

test('conversation has correct fillable attributes', function () {
    $conversation = new Conversation();
    
    expect($conversation->getFillable())->toBe([
        'name', 'image', 'type', 'created_by', 'is_encrypted',
    ]);
});

test('conversation can be global type', function () {
    $conversation = Conversation::factory()->global()->make();
    
    expect($conversation->type)->toBe('global');
});

test('conversation can be private type', function () {
    $conversation = Conversation::factory()->private()->make();
    
    expect($conversation->type)->toBe('private');
});

// ============================================
// Tests du modèle Message
// ============================================

test('message has correct fillable attributes', function () {
    $message = new Message();
    
    expect($message->getFillable())->toBe([
        'conversation_id', 'user_id', 'content',
    ]);
});

test('message created_at is cast to datetime', function () {
    $message = new Message();
    $casts = $message->getCasts();
    
    expect($casts['created_at'])->toBe('datetime');
});

// ============================================
// Tests du modèle FriendRequest
// ============================================

test('friend request has correct fillable attributes', function () {
    $friendRequest = new FriendRequest();
    
    expect($friendRequest->getFillable())->toBe([
        'sender_id', 'receiver_id', 'status', 'conversation_id',
    ]);
});

test('friend request default status is pending', function () {
    $friendRequest = FriendRequest::factory()->make();
    
    expect($friendRequest->status)->toBe('pending');
});

test('friend request can be accepted', function () {
    $friendRequest = FriendRequest::factory()->accepted()->make();
    
    expect($friendRequest->status)->toBe('accepted');
});

test('friend request can be rejected', function () {
    $friendRequest = FriendRequest::factory()->rejected()->make();
    
    expect($friendRequest->status)->toBe('rejected');
});
