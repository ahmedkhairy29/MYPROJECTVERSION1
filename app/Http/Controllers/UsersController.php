<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;
use App\Models\Department;


class UsersController extends Controller
{
    // This helper function will be used to send consistent responses
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    // REGISTER method
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'device_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'device_id' => $request->device_id,
            'activation_token' => Str::random(60),
            'is_active' => false
        ]);
    
        // Comment out email if you want manual activation only
        // Mail::to($user->email)->send(new UserActivationMail($user));
    
        return $this->responseJson(true, 'User registered. Use /api/activate/{token} to activate.', $user, 201);
    }
     
    // LOGIN method
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
    
        if (!$user) {
            return $this->responseJson(false, 'Invalid credentials', null, 401);
        }
    
        if (!$user->is_active) {
            return $this->responseJson(false, 'Account is not activated. Please check your email.', null, 400);
        }
    
        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->responseJson(false, 'Invalid credentials', null, 401);
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
    


    // PROFILE method
    public function profile()
    {
        return $this->responseJson(true, 'Profile fetched', auth()->user());

    }

    // LOGOUT method (invalidate token and logout user)
    public function logout()
    {
        try {
            // Invalidate the current JWT token
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->responseJson(true, 'Successfully logged out and token invalidated');
        } catch (JWTException $e) {
            return $this->responseJson(false, 'Failed to logout, token invalidation error', null, 500);
        }
    }
}
