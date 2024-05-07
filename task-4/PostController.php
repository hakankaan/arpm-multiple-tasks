<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Exceptions\PostCreationException;
use App\Exceptions\InvalidParameterException;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function createPost(Request $request)
    {
        try {
            $authorId = $request->input('author_id');
            $title = $request->input('title');
            $categoryId = $request->input('category_id');

            if (!$authorId || !$title || !$categoryId) {
                throw new InvalidParameterException("Missing or invalid parameters.");
            }

            $message = $this->postService->createPost((int) $authorId, $title, (int) $categoryId);
            return response()->json(['message' => $message], 201);
        } catch (InvalidParameterException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } catch (PostCreationException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}