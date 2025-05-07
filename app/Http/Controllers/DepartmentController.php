<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DepartmentController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ], $code);
}

    public function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
        }
    
        $user = auth()->user();

        $department = $user->departments()->create([
            'name' => $request->name
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
    
        $department = auth()->user()->departments()->find($id);
    
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
        $department = auth()->user()->departments()->find($id);
    
        if (!$department) {
            return $this->responseJson(false, 'Department not found', null, 404);
        }
    
        $department->delete();
    
        return $this->responseJson(true, 'Department deleted successfully', null, 200);
    }


    public function getDepartments()
    {
        $departments = auth()->user()->departments()->latest()->paginate(10);


    
    
        return $this->responseJson(true, 'Departments fetched successfully', $departments, 200);
    } 
}
