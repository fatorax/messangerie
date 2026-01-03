<?php

use App\Models\User;

// ============================================
// Tests d'accès aux pages
// ============================================

test('guest can access login page', function () {
    $response = $this->get(route('login'));
    
    $response->assertStatus(200);
});

test('guest can access register page', function () {
    $response = $this->get(route('register'));
    
    $response->assertStatus(200);
});

test('guest cannot access dashboard', function () {
    $response = $this->get(route('dashboard'));
    
    $response->assertRedirect(route('login'));
});

test('guest cannot access settings', function () {
    $response = $this->get(route('settings'));
    
    $response->assertRedirect(route('login'));
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    $response->assertStatus(200);
});

test('authenticated user can access settings', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('settings'));
    
    $response->assertStatus(200);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post(route('logout'));
    
    $this->assertGuest();
});

// Note: L'application utilise le middleware 'verified' sur les routes authentifiées
// Ce test vérifie que le middleware est bien appliqué
test('verified middleware is applied to dashboard route', function () {
    // Vérifie que la route dashboard a bien le middleware verified
    $route = app('router')->getRoutes()->getByName('dashboard');
    
    expect($route->middleware())->toContain('verified');
});

// ============================================
// Tests de validation inscription
// ============================================

test('registration requires pseudonyme', function () {
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password-confirm' => 'Password123!',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('pseudonyme');
});

test('registration requires valid email', function () {
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'johndoe',
        'email' => 'invalid-email',
        'password' => 'Password123!',
        'password-confirm' => 'Password123!',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('email');
});

test('registration requires password confirmation', function () {
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('password-confirm');
});

test('registration requires unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'johndoe',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password-confirm' => 'Password123!',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('email');
});

test('registration requires unique pseudonyme', function () {
    User::factory()->create(['username' => 'existinguser']);
    
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'existinguser',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password-confirm' => 'Password123!',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('pseudonyme');
});

test('registration requires CGV acceptance', function () {
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password-confirm' => 'Password123!',
    ]);
    
    $response->assertSessionHasErrors('rgpd');
});

test('registration requires strong password', function () {
    $response = $this->post(route('register.submit'), [
        'firtName' => 'John',
        'lastName' => 'Doe',
        'pseudonyme' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'weakpass', // pas de majuscule, chiffre, symbole
        'password-confirm' => 'weakpass',
        'rgpd' => '1',
    ]);
    
    $response->assertSessionHasErrors('password');
});

// ============================================
// Tests de validation connexion
// ============================================

test('login requires email', function () {
    $response = $this->post(route('login.submit'), [
        'password' => 'password123',
    ]);
    
    $response->assertSessionHasErrors('email');
});

test('login requires password', function () {
    $response = $this->post(route('login.submit'), [
        'email' => 'john@example.com',
    ]);
    
    $response->assertSessionHasErrors('password');
});

// Note: Le système de login utilise un verify_token, pas les credentials classiques
// Le login redirige avec un message d'erreur en session, pas une erreur de validation
test('login with wrong credentials shows error message', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'verify_token' => 'correct-token',
    ]);
    
    $response = $this->post(route('login.submit'), [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);
    
    // Le système redirige avec un message d'erreur en flash session
    $response->assertRedirect();
    $response->assertSessionHas('error');
});
