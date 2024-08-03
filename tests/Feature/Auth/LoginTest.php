<?php

use App\Models\User;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {

    // arrange
    $user = User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    // act
    postJson(route('login'), [
        'email'    => 'johndoe@example.com',
        'password' => 'password',
    ])->assertNoContent();

    // asserts
    assertAuthenticatedAs($user);
});

it('should be able to check if the email and password is valid', function ($email, $password) {
    User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    postJson(route('login'), [
        'email'    => $email,
        'password' => $password,
    ])->assertJsonValidationErrors([
        'email' => __('auth.failed'),
    ]);
})->with([
    'wrong email'    => ['wrongemail@example.com', 'password'],
    'wrong password' => ['johndoe@example.com', 'wrong_password'],
]);

it('should be able to check if the email is valid', function () {
    User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    postJson(route('login'), [
        'email'    => 'johndoe',
        'password' => 'password',
    ])->assertJsonValidationErrors([
        'email' => __('validation.email', ['attribute' => 'email']),
    ]);
});

test('required fields', function () {
    $user = User::factory()->create(['email' => 'johndoe@example.com', 'password' => 'password']);

    postJson(route('login'), [
        'email'    => '',
        'password' => '',
    ])->assertJsonValidationErrors([
        'email'    => __('validation.required', ['attribute' => 'email']),
        'password' => __('validation.required', ['attribute' => 'password']),
    ]);
});
