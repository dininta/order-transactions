<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => $validator->errors(),
                'result' => null
            ]);
        }

        $credentials = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::create($credentials);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 200,
            'message' => 'User successfully registered!',
            'result' => ['token' => $token]
        ]);
    }

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

    public function logout(Request $request)
    {
        JWTAuth::invalidate();

        return [
            'status' => 200,
            'message' => 'Successfully logged out the user!',
            'result' => null
        ];        
    }
}
