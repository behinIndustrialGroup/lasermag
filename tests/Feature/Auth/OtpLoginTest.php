<?php

use Illuminate\Support\Facades\Cache;
use App\Models\User;

it('sends otp and stores it in cache', function () {
    $response = $this->postJson('/otp/send', ['phone' => '09123456789']);
    $response->assertOk();
    expect(Cache::get('otp_09123456789'))->not->toBeNull();
});

it('logs in user with valid otp', function () {
    Cache::put('otp_09123456789', '123456', now()->addMinutes(5));
    $response = $this->postJson('/otp/verify', ['phone' => '09123456789', 'otp' => '123456']);
    $response->assertOk();
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['phone' => '09123456789']);
});

it('rejects invalid otp', function () {
    Cache::put('otp_09123456789', '123456', now()->addMinutes(5));
    $response = $this->postJson('/otp/verify', ['phone' => '09123456789', 'otp' => '654321']);
    $response->assertStatus(422);
    $this->assertGuest();
});
