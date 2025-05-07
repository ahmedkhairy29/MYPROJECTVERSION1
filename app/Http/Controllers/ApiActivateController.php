<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiActivateController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    // 1. Activate using the token in the URL
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return $this->responseJson(false, 'Invalid or expired activation token', null, 404);
        }

        $user->is_active = true;
        $user->activation_token = null; // clear token after activation
        $user->save();

        return $this->responseJson(true, 'Account activated successfully', $user);
    }

    // 2. Manual activation (e.g., admin side)
    public function manualActivate(Request $request)
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
        $user->activation_token = null;
        $user->save();

        return $this->responseJson(true, 'User manually activated successfully', $user);
    }

    // 3. Activate with token sent in the body
    public function activateWithToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = User::where('activation_token', $request->token)->first();

        if (!$user) {
            return $this->responseJson(false, 'Invalid activation token', null, 404);
        }

        $user->is_active = true;
        $user->activation_token = null;
        $user->save();

        return $this->responseJson(true, 'Account activated using token', $user);
    }
}
