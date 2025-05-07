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
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivationMail;


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

        Mail::to($user->email)->send(new UserActivationMail($user));

    return $this->responseJson(true, 'User registered successfully. Please check your email for activation.', $user, 201);
    } 


    public function activate($token)
{
    $user = User::where('activation_token', $token)->first();

    if (!$user) {
        return $this->responseJson(false, 'Invalid or expired activation token', null, 400);
    }

    // Activate the user and clear the activation token
    $user->is_active = true;
    $user->activation_token = null;
    $user->save();

    return $this->responseJson(true, 'Account successfully activated', null, 200);
}
public function addDepartment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
    }

    $department = Department::create([
        'name' => $request->name,
        'user_id' => auth()->id() // Associate department with the logged-in user
    ]);

    return $this->responseJson(true, 'Department created successfully', $department, 201);
}
public function updateDepartment(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
    }

    $department = Department::where('user_id', auth()->id())->find($id);

    if (!$department) {
        return $this->responseJson(false, 'Department not found', null, 404);
    }

    $department->update([
        'name' => $request->name
    ]);

    return $this->responseJson(true, 'Department updated successfully', $department, 200);
}
public function deleteDepartment($id)
{
    $department = Department::where('user_id', auth()->id())->find($id);

    if (!$department) {
        return $this->responseJson(false, 'Department not found', null, 404);
    }

    $department->delete();

    return $this->responseJson(true, 'Department deleted successfully', null, 200);
}
public function getDepartments()
{
    $departments = auth()->user()->departments()->get();


    return $this->responseJson(true, 'Departments fetched successfully', $departments, 200);
}



    // LOGIN method
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'device_id' => 'required'
    ]);

    if ($validator->fails()) {
        return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
    }

    $credentials = $request->only('email', 'password');

    if (! $token = JWTAuth::attempt($credentials)) {
        return $this->responseJson(false, 'Invalid credentials', null, 401);
    }

    $user = JWTAuth::user();

    // Check if the user is active
    if (!$user->is_active) {
        return $this->responseJson(false, 'Account is not activated. Please check your email.', null, 400);
    }

    return $this->responseJson(true, 'Login successful', [
        'token' => $token,
        'user' => $user
    ], 200);
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
