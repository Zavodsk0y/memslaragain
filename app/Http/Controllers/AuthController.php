<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(SignupRequest $request): JsonResponse
    {
        $data = $request->json()->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'user_token' => $token
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) throw new ApiException('Ошибка входа', 401);

        $user = $request->user();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Login successful',
            'user_token' => $user->createToken('user_token')->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Logout successful',
        ]);
    }
}
