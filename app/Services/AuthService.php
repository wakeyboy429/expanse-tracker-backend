<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->create($data);
        $token = $user->createToken('AuthToken')->accessToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function login(array $data): array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = (string) $user->createToken('AuthToken')->accessToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
