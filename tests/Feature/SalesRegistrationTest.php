<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

it('does not expose a public sales registration endpoint', function () {
    $this->postJson('/api/register', [
        'nama' => 'Sales Baru',
        'email' => 'sales@example.com',
        'password' => 'password',
        'role' => 'sales',
    ])->assertNotFound();
});

it('allows admin to register sales users', function () {
    $admin = User::create([
        'nama' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    Sanctum::actingAs($admin);

    $this->postJson('/api/sales/register', [
        'nama' => 'Sales Baru',
        'email' => 'sales@example.com',
        'password' => 'password',
    ])->assertCreated()
        ->assertJsonPath('user.role', 'sales');

    $this->assertDatabaseHas('users', [
        'email' => 'sales@example.com',
        'role' => 'sales',
    ]);
});