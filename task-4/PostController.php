<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function createPost(Request $request)
    {
        $authorId = $request->input('author_id');
        $title = $request->input('title');
        $categoryId = $request->input('category_id');

        if (!$authorId || !$title || !$categoryId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $result = $this->postService->createPost((int) $authorId, $title, (int) $categoryId);

        if ($result === "Post created successfully!") {
            return response()->json(['message' => $result], 201);
        } else {
            return response()->json(['error' => $result], 400);
        }
    }
}
