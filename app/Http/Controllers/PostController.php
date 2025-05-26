<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected function responseJson($status, $message, $data = null, $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function addPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->responseJson(false, 'Validation failed', $validator->errors(), 422);
        }

        $user = auth()->user();

        $post = $user->posts()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $this->responseJson(true, 'Post created successfully', $post, 201);
    }

    public function getUserPosts()
    {
        $user = auth()->user();
        $posts = $user->posts;

        return $this->responseJson(true, 'User posts retrieved', $posts);
    }
}
