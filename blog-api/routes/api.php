<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('{article}', [ArticleController::class, 'show']);
    Route::post('{article}/like', [ArticleController::class, 'incrementLikes']);
    Route::post('{article}/view', [ArticleController::class, 'incrementViews']);
    Route::get('{article}/get-comments', [CommentController::class, 'getComments']);
    Route::post('{article}/comment', [CommentController::class, 'createComment']);
});

