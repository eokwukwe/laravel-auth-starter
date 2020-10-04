<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\UserRegistrationRequest;

class RegisterController extends Controller
{
    /**
     * Register new user
     *
     * @param Illuminate\Foundation\Http\FormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegistrationRequest $request)
    {
        $user = $this->create($request->all());

        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successful. Please, check your mail to verify your email'
        ], 201);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
