<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $data = request()->validate([
            'name'     => 'required',
            'email'    => ['required', 'email', 'unique:App\Models\User,email'],
            'password' => ['required', 'confirmed', 'min:6', 'max:8'],
        ]);

        User::create($data);

        return response()->noContent();
    }
}
