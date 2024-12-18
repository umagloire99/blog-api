<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Upload;
use App\Traits\ApiResponse;
use App\Enums\UploadUseAsEnum;
use App\Http\Requests\PostRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostListingQueryParamsRequest;

/**
 * @group Posts
 *
 * APIs for posts.
 */
class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the posts.
     *
     * @queryParam page integer The page number for pagination. Example: 1
     * @queryParam per_page integer Number of results per page. Defaults to 10. Example: 10
     * @queryParam search string Search term to filter blog posts. Example: Laravel
     * @queryParam tags[] string Array of tags slug to filter posts. Example: tags[]=laravel&tags[]=php
     */
    public function index(PostListingQueryParamsRequest $request)
    {
        $posts = Post::search($request->search, function ($query) use ($request) {
            $query->with(['user', 'image', 'tags'])->when($request->tags, function ($query) use ($request) {
                $query->whereHas('tags', function ($query) use ($request) {
                    $query->whereIn('slug', $request->tags);
                });
            });
        })->paginate();

        return $this->success(
            'Posts successfully retrieved',
            $posts
        );
    }

    /**
     * Store a newly created post in storage.
     *
     * @authenticated
     * @header Content-Type multipart/form-data
     */
    public function store(PostRequest $request)
    {
        $post = $request->user()->posts()->create($request->only(['title', 'content']));

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        if ($request->image) {
            $post->handleUploads($request->image, UploadUseAsEnum::IMAGE);
        }

        if ($request->gallery) {
            $post->handleUploads($request->gallery, UploadUseAsEnum::GALLERY);
        }

        return $this->success(
            'The post has been successfully created',
            $post->load(['tags', 'image', 'gallery']),
            201
        );
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post = $post->load(['image', 'gallery',  'tags']);

        return $this->success(
            'Show post details.',
            $post
        );
    }


    /**
     * Update post.
     *
     * @authenticated
     * @header Content-Type multipart/form-data
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->only(['title', 'content']));

        if ($request->image) {
            if ($image = $post->image) {
                $image->delete();
            }

            $post->handleUploads($request->image, UploadUseAsEnum::IMAGE);
        }

        if ($request->gallery) {
            $post->handleUploads($request->gallery, UploadUseAsEnum::GALLERY);
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

        return $this->success(
            'The post has been successully updated.',
            $post->load(['image', 'gallery', 'tags'])
        );
    }


    /**
     * Delete a specific post.
     *
     * @authenticated
     *
     * @param  Post $post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->success(
            'The post has been successully deleted'
        );
    }

    /**
     * Delete a specific file from a post.
     *
     * @authenticated
     *
     * @param  Post $post
     * @param  Upload $upload
     */
    public function removeUpload(Post $post, Upload $upload)
    {
        $upload->delete();

        return $this->success(
            'The file has been successully deleted',
            $post->load(['image', 'gallery', 'tags'])
        );
    }

    /**
     * Get comments for a specific post.
     *
     * @authenticated
     *
     * @queryParam page integer The page number for pagination. Example: 1
     * @queryParam per_page integer Number of results per page. Defaults to 10. Example: 10
     *
     */
    public function comments(Request $request, Post $post)
    {
        $comments = $post->comments()->paginate();

        return $this->success(
            'Comments successully retrieved',
            $comments
        );
    }

    /**
     * Add a comment to specified post.
     *
     * @authenticated
     *
     * @param  CommentRequest $request
     * @param  Post $post
     */
    public function addComment(CommentRequest $request, Post $post)
    {
        $comment = $request->user()->comment($post, $request->content);

        return $this->success(
            'Comment added successfully',
            $comment
        );
    }
}
