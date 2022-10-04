<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Companypost\Http\Controllers\Frontend\PostController;


Route::get('/posts', [PostController::class, 'index'])->name('posts');

Route::get('/post-detail/{id}/{slug}', [PostController::class, 'detail'])->name('post.detail');

Route::get('/post-tags/{id}/{slug}', [PostController::class, 'postOftags'])->name('post.tags');

