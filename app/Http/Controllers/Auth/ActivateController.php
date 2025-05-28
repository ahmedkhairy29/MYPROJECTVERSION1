<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActivateController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function activateByEmail(Request $request)
{
    
    $request->validate([
        'token' => 'required|string',
        'email' => 'required|email',
    ]);

    
    $user = User::where('email', $request->email)
                ->where('activation_token', $request->token)
                ->first();

    if (!$user) {
        return $this->responseJson(false, 'Invalid token or email', null, 400);
    }

    if ($user->is_active) {
        return $this->responseJson(false, 'User is already activated', null, 400);
    }

    
    $user->is_active = true;
    $user->activation_token = null;
    $user->save();

    return $this->responseJson(true, 'User activated successfully', [
        'name' => $user->name,
        'email' => $user->email
    ]);
}

} 
