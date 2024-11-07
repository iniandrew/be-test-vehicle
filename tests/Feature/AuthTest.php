<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

it('can register', function () {
    $payload = [
        'name' => 'Andrew',
        'email' => 'andrew@mail.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/auth/register', $payload);

    expect($response->status())->toBe(Response::HTTP_CREATED)
        ->and($response->json('code'))->toBe(Response::HTTP_CREATED)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.name'))->toBe('Andrew')
        ->and($response->json('data.email'))->toBe('andrew@mail.com');
});

it('can login', function () {
    $payload = [
        'email' => 'andrew@mail.com',
        'password' => 'password',
    ];

    $response = $this->postJson('/api/auth/login', $payload);

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('code'))->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.token_type'))->toBe('bearer');
});

it('can not login with wrong credential', function () {
    $payload = [
        'email' => 'andrew@mail.com',
        'password' => 'passwordxx',
    ];

    $response = $this->postJson('/api/auth/login', $payload);

    expect($response->status())->toBe(Response::HTTP_UNAUTHORIZED)
        ->and($response->json('status'))->toBe('error');
});

it('can logout', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/auth/logout');

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('code'))->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success');
});

afterAll(function () {
    /**
     * Clean up the database after all tests have been completed.
     * Using the truncate method because Laravel MongoDB does not support the database testing traits.
     */
    User::query()->truncate();
});
