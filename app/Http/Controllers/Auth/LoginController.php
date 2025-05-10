<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

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
        $credentials = $request->only('email', 'password');
    
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
    
        if ($validator->fails()) {
            return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
        }
    
       $user = User::where('email', $credentials['email'])->first();

        // Check credentials and user status
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->responseJson(false, 'Invalid credentials', null, 401);
        }

        if (!$user->is_active) {
            return $this->responseJson(false, 'Account is not activated. Please check your email.', null, 403);
        }
        
    
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->responseJson(false, 'Login failed', null, 401);
            }
        } catch (JWTException $e) {
            return $this->responseJson(false, 'Could not create token', null, 500);
        }
    
        return $this->responseJson(true, 'Login successful', [
            'token' => $token,
            'user' => $user
        ]);
    }
    
}
