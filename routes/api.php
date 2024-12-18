<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;


// Auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Don't require authentication
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post:slug}/comments', [PostController::class, 'comments'])->name('posts.comments');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');

// Requires authentication
Route::middleware('auth:sanctum')->group(function() {
    # Posts
    Route::group(['prefix' => 'posts'], function () {
        Route::post('store', [PostController::class, 'store'])->name('posts.store')->can('create', Post::class);
        Route::patch('{post:slug}/update', [PostController::class, 'update'])->name('posts.update')->can('update', 'post');
        Route::delete('{post:slug}/destroy', [PostController::class, 'destroy'])->name('posts.destroy')->can('delete', 'post');
        Route::post('{post:slug}/comments/add', [PostController::class, 'addComment'])->name('posts.comments.add');
        Route::delete('{post:slug}/uploads/{upload:ref}/remove', [PostController::class, 'removeUpload'])->name('posts.uploads.remove');
    });

     # Comments
     Route::group(['prefix' => 'comments'], function () {
        Route::post('{comment}/comments/add', [CommentController::class, 'addComment'])->name('comments.comment.add');
        Route::put('{comment}/update', [CommentController::class, 'update'])->name('comments.update')->can('update', 'comment');
        Route::delete('{comment}/destroy', [CommentController::class, 'destroy'])->name('comments.destroy')->can('delete', 'comment');
    });

      # Tags
      Route::group(['prefix' => 'tags'], function () {
        Route::post('store', [TagController::class, 'store'])->name('tags.store')->can('create', Tag::class);;
        Route::put('{tag:slug}/update', [TagController::class, 'update'])->name('tags.update')->can('update', 'tag');
        Route::delete('{tag:slug}/destroy', [TagController::class, 'destroy'])->name('tags.destroy')->can('delete', 'tag');
    });

    # Users
    Route::group(['prefix' => 'users', 'middleware' => ['role:admin']], function() {
        Route::get('', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('store', [UserController::class, 'store'])->name('users.store');
        Route::get('{user:username}', [UserController::class, 'show'])->name('users.show');
        Route::get('{user:username}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('{user:username}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('{user:user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    });

    # Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
