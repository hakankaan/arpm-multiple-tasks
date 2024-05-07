<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Services\PostService;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function createPost(CreatePostRequest $request)
    {
        try {
            $message = $this->postService->createPost($createPostRequest->validated());
            return response()->json(['message' => $message], 201);
        } catch (InvalidParameterException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } catch (PostCreationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
