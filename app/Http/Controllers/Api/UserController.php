<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Response;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => $validator->errors(),
                'result' => null
            ]);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 200,
                'message' => 'Token generated',
                'result' => ['token' => $token]
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Failed to authenticate user',
            'result' => null
        ]);
    }
}
