<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $data = request()->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return response()->noContent();
    }
}
