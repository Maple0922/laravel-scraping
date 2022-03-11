<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ScrapeController;

// Route::middleware('auth:sanctum')->group(function () {
Route::get('/medias', [MediaController::class, 'index']);
Route::get('/medias/{mediaId}/articles', [MediaController::class, 'articles']);
Route::post('/scrape', [ScrapeController::class, 'run']);
// });
