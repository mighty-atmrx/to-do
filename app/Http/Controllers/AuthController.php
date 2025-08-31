<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create($data);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Registration failed.', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Registration failed. Please try again.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!auth()->attempt($request->validated())) {
                return response()->json([
                    'message' => 'Invalid credentials.'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Login failed.', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Authorization failed.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Logout failed.', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Logout failed.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me(Request $request)
    {
        dd(111);
        return response()->json($request->user());
    }
}
