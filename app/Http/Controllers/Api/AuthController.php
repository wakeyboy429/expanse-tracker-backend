<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    #[OA\Post(path: "/api/signup", operationId: "signUp", summary: "Register a new user", description: "Creates a user account and returns an access token.", tags: ["Authentication"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "application/x-www-form-urlencoded",
            schema: new OA\Schema(
                required: ["name","email","password","password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "Password123"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "Password123")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Registration details will appear here after execution."
    )]
    #[OA\Response(response: 422, description: "Validation error")]
    public function signUp(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'data'    => [
                'user'  => new UserResource($result['user']),
                'token' => $result['token'],
            ],
            'message' => 'User registered successfully.',
        ], 201);
    }

    #[OA\Post(path: "/api/login", operationId: "login", summary: "Login existing user", description: "Authenticates user and returns an access token.", tags: ["Authentication"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "application/x-www-form-urlencoded",
            schema: new OA\Schema(
                required: ["email","password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "Password123")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Login details will appear here after execution."
    )]
    #[OA\Response(response: 401, description: "Invalid credentials")]
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'data'    => [
                'user'  => new UserResource($result['user']),
                'token' => $result['token'],
            ],
            'message' => 'User logged in successfully.',
        ]);
    }
}
