<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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


   public function profile()
{
    try {
        
        $user = auth()->user()->load('department');
        
        return $this->responseJson(true, 'User profile retrieved successfully', $user);
    } catch (\Exception $e) {
        return $this->responseJson(false, 'Failed to retrieve user profile', null, 500);
    }
}


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
    
    
public function assignDepartment(Request $request, $userId)
{
    $validator = Validator::make($request->all(), [
        'department_id' => 'required|exists:departments,id'
    ]);

    if ($validator->fails()) {
        return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
    }

    $user = User::findOrFail($userId);
    $user->department_id = $request->department_id;
    $user->save();

    return $this->responseJson(true, 'Department assigned successfully', $user);
}
}
