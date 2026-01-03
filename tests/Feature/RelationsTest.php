<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\FriendRequest;

// ============================================
// Tests des relations User
// ============================================

test('user can have many conversations', function () {
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    $user->conversations()->attach($conversation->id, ['role' => 'member']);
    
    expect($user->conversations)->toHaveCount(1);
    expect($user->conversations->first()->id)->toBe($conversation->id);
});

test('user can have many messages', function () {
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    Message::factory()->count(3)->create([
        'user_id' => $user->id,
        'conversation_id' => $conversation->id,
    ]);
    
    expect($user->messages)->toHaveCount(3);
});

test('user can send friend requests', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    
    FriendRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);
    
    expect($sender->sentFriendRequests)->toHaveCount(1);
});

test('user can receive friend requests', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    
    FriendRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);
    
    expect($receiver->receivedFriendRequests)->toHaveCount(1);
});

// ============================================
// Tests des relations Conversation
// ============================================

test('conversation can have many users', function () {
    $conversation = Conversation::factory()->create();
    $users = User::factory()->count(3)->create();
    
    foreach ($users as $user) {
        $conversation->users()->attach($user->id, ['role' => 'member']);
    }
    
    expect($conversation->users)->toHaveCount(3);
});

test('conversation can have many messages', function () {
    $conversation = Conversation::factory()->create();
    $user = User::factory()->create();
    
    Message::factory()->count(5)->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
    ]);
    
    expect($conversation->messages)->toHaveCount(5);
});

// ============================================
// Tests des relations Message
// ============================================

test('message belongs to conversation', function () {
    $conversation = Conversation::factory()->create();
    $user = User::factory()->create();
    
    $message = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
    ]);
    
    expect($message->conversation->id)->toBe($conversation->id);
});

test('message belongs to user', function () {
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    $message = Message::factory()->create([
        'user_id' => $user->id,
        'conversation_id' => $conversation->id,
    ]);
    
    expect($message->user->id)->toBe($user->id);
});

test('message can be marked as read', function () {
    \Illuminate\Support\Facades\Event::fake();
    
    $author = User::factory()->create();
    $reader = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    $message = Message::factory()->create([
        'user_id' => $author->id,
        'conversation_id' => $conversation->id,
    ]);
    
    expect($message->isReadBy($reader->id))->toBeFalse();
    
    $message->markAsReadBy($reader->id);
    
    expect($message->isReadBy($reader->id))->toBeTrue();
});

test('message author cannot mark own message as read', function () {
    $author = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    $message = Message::factory()->create([
        'user_id' => $author->id,
        'conversation_id' => $conversation->id,
    ]);
    
    $message->markAsReadBy($author->id);
    
    expect($message->isReadBy($author->id))->toBeFalse();
});

// ============================================
// Tests des relations FriendRequest
// ============================================

test('friend request belongs to sender', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    
    $friendRequest = FriendRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);
    
    expect($friendRequest->sender->id)->toBe($sender->id);
});

test('friend request belongs to receiver', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    
    $friendRequest = FriendRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
    ]);
    
    expect($friendRequest->receiver->id)->toBe($receiver->id);
});
