<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $credentials = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credentials);
        if ($token) {
            return response()->json($token, 200);
        }
        return response()->json(['error' => 'invalid user or password'], 403);
    }

    public function logout() {
        return 'logout';
    }

    public function refresh() {
        $token = auth('api')->refresh();

        return response()->json($token, 200);
    }

    public function me() {
        return response()->json(auth()->user(), 200);
    }
}
