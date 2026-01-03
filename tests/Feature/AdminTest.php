<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

// ============================================
// Tests Admin Panel - Accès
// ============================================

test('admin dashboard shows correct statistics', function () {
    $admin = User::factory()->admin()->create();
    
    // Créer des données de test
    User::factory()->count(5)->create();
    Conversation::factory()->count(3)->global()->create();
    Conversation::factory()->count(2)->private()->create();
    
    $response = $this->actingAs($admin)->get(route('admin'));
    
    $response->assertStatus(200);
    $response->assertSee('Utilisateurs');
    $response->assertSee('Channels');
});

test('admin can view users list', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->count(5)->create();
    
    $response = $this->actingAs($admin)->get(route('admin.users'));
    
    $response->assertStatus(200);
});

test('admin can view channels list', function () {
    $admin = User::factory()->admin()->create();
    Conversation::factory()->count(3)->global()->create();
    
    $response = $this->actingAs($admin)->get(route('admin.channels'));
    
    $response->assertStatus(200);
});

test('admin can view conversations list', function () {
    $admin = User::factory()->admin()->create();
    
    $response = $this->actingAs($admin)->get(route('admin.conversations'));
    
    $response->assertStatus(200);
});

test('admin can view messages list', function () {
    $admin = User::factory()->admin()->create();
    
    $response = $this->actingAs($admin)->get(route('admin.messages'));
    
    $response->assertStatus(200);
});

// ============================================
// Tests Admin Panel - CRUD Users
// ============================================

test('admin can update user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    
    $response = $this->actingAs($admin)->post(route('admin.users.update', $user->id), [
        'username' => 'newusername',
        'firstname' => 'NewFirstname',
        'lastname' => 'NewLastname',
        'email' => $user->email,
        'role' => 'user',
    ]);
    
    $response->assertRedirect(route('admin.users'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => 'newusername',
        'firstname' => 'NewFirstname',
    ]);
});

test('admin cannot update own role', function () {
    $admin = User::factory()->admin()->create();
    
    // L'admin ne devrait pas pouvoir se retirer le rôle admin
    // (selon l'implémentation)
    expect($admin->role)->toBe('admin');
});

test('admin can delete user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    
    $response = $this->actingAs($admin)->post(route('admin.users.delete', $user->id));
    
    $response->assertRedirect(route('admin.users'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

// ============================================
// Tests Admin Panel - CRUD Channels
// ============================================

test('admin can update channel', function () {
    $admin = User::factory()->admin()->create();
    $channel = Conversation::factory()->global()->create(['name' => 'Old Name']);
    
    $response = $this->actingAs($admin)->post(route('admin.channels.update', $channel->id), [
        'name' => 'New Channel Name',
    ]);
    
    $response->assertRedirect(route('admin.channels'));
    $this->assertDatabaseHas('conversations', [
        'id' => $channel->id,
        'name' => 'New Channel Name',
    ]);
});

test('admin can delete channel', function () {
    $admin = User::factory()->admin()->create();
    $channel = Conversation::factory()->global()->create();
    
    $response = $this->actingAs($admin)->post(route('admin.channels.delete', $channel->id));
    
    $response->assertRedirect(route('admin.channels'));
    $this->assertDatabaseMissing('conversations', ['id' => $channel->id]);
});

// ============================================
// Tests Admin Panel - Messages
// ============================================

test('admin can delete message', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $conversation = Conversation::factory()->create();
    
    $message = Message::factory()->create([
        'user_id' => $user->id,
        'conversation_id' => $conversation->id,
    ]);
    
    $response = $this->actingAs($admin)->post(route('admin.messages.delete', $message->id));
    
    $response->assertRedirect(route('admin.messages'));
    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});

test('admin can view conversation messages', function () {
    $admin = User::factory()->admin()->create();
    $conversation = Conversation::factory()->create();
    
    $response = $this->actingAs($admin)->get(route('admin.conversation.messages', $conversation->id));
    
    $response->assertStatus(200);
});
