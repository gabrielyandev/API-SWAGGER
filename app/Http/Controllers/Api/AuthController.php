<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/login",
        summary: "Realiza o login e retorna o Token Bearer",
        tags: ["Autenticação"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/LoginRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login realizado com sucesso",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "1|laravel_sanctum_token_aqui"),
                        new OA\Property(property: "message", type: "string", example: "Login realizado com sucesso")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Credenciais inválidas"),
            new OA\Response(response: 422, description: "Erro de validação")
        ]
    )]
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        // Gera o token Sanctum
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Login realizado com sucesso'
        ], 200);
    }
}
