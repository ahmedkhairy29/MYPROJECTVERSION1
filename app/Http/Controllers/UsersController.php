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
