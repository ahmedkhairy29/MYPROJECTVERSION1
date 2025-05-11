<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email',
            'activation_token' => 'required|string'
        ]);
    
        $user = User::where('email', $request->email)
                    ->where('activation_token', $request->activation_token)
                    ->first();
    
        if (!$user) {
            return $this->responseJson(false, 'Invalid email or activation token', null, 404);
        }
    
        if ($user->is_active) {
            return $this->responseJson(false, 'User is already activated', null, 400);
        }
    
        $user->is_active = true;
        $user->activation_token = null; // Optional: clear the token
        $user->save();
    
        return $this->responseJson(true, 'User activated successfully', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
