<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login request.
     *
     * Validates credentials and returns an access token if authentication succeeds.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 400);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Login failed', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Logs out the authenticated user by deleting their current access token.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse JSON response indicating logout status.
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
                return response()->json(['message' => 'Logged out successfully']);
            } else {
                return response()->json(['message' => 'No active token found'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Logout failed', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Retrieve the authenticated user's information.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse JSON response containing user data.
     */
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
}
