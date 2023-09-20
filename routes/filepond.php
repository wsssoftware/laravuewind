<?php

use Illuminate\Support\Facades\Route;
use Laravuewind\Http\Controllers\FilePond\ChunkController;
use Laravuewind\Http\Controllers\FilePond\DeleteController;
use Laravuewind\Http\Controllers\FilePond\UploadController;

Route::prefix('laravuewind/filepond')->group(function () {
    Route::patch('/', ChunkController::class)->name('lvw.filepond');
    Route::post('/process', UploadController::class);
    Route::delete('/process', DeleteController::class);
});