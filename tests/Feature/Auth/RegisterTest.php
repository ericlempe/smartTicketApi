<?php

use App\Models\User;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to register a user', function () {

    postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'johndoe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertNoContent();

    // asserts
    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);
});

it('should be able to check if the email is valid', function () {
    postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'invalid-email',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertJsonValidationErrors([
        'email' => __('validation.email', ['attribute' => 'email']),
    ]);
});

it('should be able to check if the email already exists', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'johndoe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])->assertJsonValidationErrors([
        'email' => __('validation.unique', ['attribute' => 'email']),
    ]);
});

it('should be able to check if the password fields are different', function () {
    User::factory()->create(['email' => 'johndoe@example.com']);

    postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'johndoe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'another_password',
    ])->assertJsonValidationErrors([
        'password' => __('validation.confirmed', ['attribute' => 'password']),
    ]);
});

test('strength password', function ($password, $rule, $value) {
    postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'johndoe@example.com',
        'password'              => $password,
        'password_confirmation' => $password,
    ])->assertJsonValidationErrors([
        'password' => __('validation.' . $rule, ['attribute' => 'password', $rule => $value]),
    ]);
})->with([
    'min validation' => ['pass', 'min', 6],
    'max validation' => ['max_password', 'max', 8],
]);

test('required fields', function () {
    postJson(route('register'), [
        'name'                  => '',
        'email'                 => '',
        'password'              => '',
        'password_confirmation' => '',
    ])->assertJsonValidationErrors([
        'name'     => __('validation.required', ['attribute' => 'name']),
        'email'    => __('validation.required', ['attribute' => 'email']),
        'password' => __('validation.required', ['attribute' => 'password']),
    ]);
});
