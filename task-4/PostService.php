<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

class PostService
{
    private PostRepositoryInterface $postRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(PostRepositoryInterface $postRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function createPost(int $author_id, string $title, int $categoryId): string
    {
        if (!$this->categoryRepository->exists($categoryId)) {
            return "Invalid category ID!";
        }

        if ($this->postRepository->existsByTitleAndAuthor($title, $author_id)) {
            return "Title already exists.";
        }

        DB::beginTransaction();
        try {
            $post = new BlogPost([
                'title' => $title,
                'author_id' => $author_id,
                'status' => 'published'
            ]);

            $this->postRepository->save($post);

            $this->postRepository->attachCategory($post->id, $categoryId);

            DB::commit();
            return "Post created successfully!";
        } catch (\Exception $e) {
            DB::rollback();
            return "Failed to create post: " . $e->getMessage();
        }
    }
}