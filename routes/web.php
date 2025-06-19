<?php

use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;


// Route for the home page (root URL)
Route::get('/', [PostController::class, 'list']);

// post routes
Route::prefix('post')->group(function () {
    // Display list of posts
    Route::get('list', [PostController::class, 'list'])->name('post.list');

    // Show add/edit form
    Route::get('add-edit', [PostController::class, 'addEdit'])->name('post.addEdit');

    // Handle store or update submission
    Route::post('store-update', [PostController::class, 'storeOrUpdate'])->name('post.storeOrUpdate');

    // Delete a post
    Route::delete('delete', [PostController::class, 'delete'])->name('post.delete');

    // Delete a post using AJAX
    Route::post('onDelete', [PostController::class, 'onDelete'])->name('post.onDelete');
});
