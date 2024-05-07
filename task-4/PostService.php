<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Exceptions\PostCreationException;
use App\Exceptions\InvalidParameterException;

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
            throw new InvalidParameterException("Invalid category ID!");
        }

        if ($this->postRepository->existsByTitleAndAuthor($title, $author_id)) {
            throw new PostCreationException("Title already exists.");
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
            throw new PostCreationException("Failed to create post: " . $e->getMessage());
        }
    }
}
