<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JWTAuth;



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
             'password' => 'required|string|min:6|confirmed',
             'device_id' => 'required'
         ]);
     
         if ($validator->fails()) {
             return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
         }

         $activationToken = Str::random(64);

     
         $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'device_id' => $request->device_id,
        'is_active' => false,
        'activation_token' => $activationToken,

         ]);

         
            
            $user->sendActivationNotification();

    
        return $this->responseJson(true, 'User registered. Check email for activation link.', [
        'name' => $user->name,
        'email' => $user->email,
        //'activation_token' => $activationToken
        ], 201);
      }
   } 