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
            'email' => 'required|email'
        ]);

       
        $user = User::where('email', $request->email)->first();

        
        if (!$user) {
            return $this->responseJson(false, 'User not found', null, 404);
        }

        
        if ($user->is_active) {
            return $this->responseJson(false, 'User is already activated', null, 400);
        }

        
        $user->is_active = true;
        $user->save();

        return $this->responseJson(true, 'User activated successfully', $user);
    }
}
