<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware('web')->get('/__test/token-mismatch', function () {
        throw new TokenMismatchException('Simulated token mismatch.');
    });
});

it('redirects web requests with csrf mismatch to login with a warning', function () {
    $user = User::factory()->lecturer()->create();

    $response = $this->actingAs($user)
        ->from(route('dashboard'))
        ->get('/__test/token-mismatch');

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('warning', 'Your session expired. Please log in again.');
});

it('returns json 419 for api clients when csrf token mismatches', function () {
    $response = $this->getJson('/__test/token-mismatch');

    $response->assertStatus(419);
    $response->assertJson([
        'message' => 'Your session expired. Please log in again.',
    ]);
});
