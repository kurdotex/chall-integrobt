<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    use ApiResponder;

    public function __construct(
        protected AuthService $authService
    ) {}

    #[OA\Post(
        path: "/api/login",
        summary: "Login and get token",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "password", type: "string")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->only('email', 'password'));

        return $this->success('Login exitoso', [
            'access_token' => $data['access_token'],
            'token_type' => $data['token_type'],
        ]);
    }

    #[OA\Post(
        path: "/api/logout",
        summary: "Logout and revoke token",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success('Sesión cerrada correctamente');
    }

    #[OA\Get(
        path: "/api/me",
        summary: "Get authenticated user details",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function me(Request $request)
    {
        return $this->success('Datos del usuario', new \App\Http\Resources\UserResource($request->user()));
    }
}
