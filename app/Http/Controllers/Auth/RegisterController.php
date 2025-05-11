<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    

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
     
         return $this->responseJson(true, 'User registered. Use /api/activate-user to activate.', $user, 201);
     }
}
