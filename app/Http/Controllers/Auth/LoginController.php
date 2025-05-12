<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
    }

    try {
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return $this->responseJson(false, 'Invalid credentials', null, 401);
        }
    } catch (JWTException $e) {
        return $this->responseJson(false, 'Could not create token', null, 500);
    }

    return $this->responseJson(true, 'User logged in successfully', [
        'token' => $token
    ]);
}


}
