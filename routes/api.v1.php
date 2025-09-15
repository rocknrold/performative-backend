<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScripturesController;

Route::prefix('v1')->group(function () {
    Route::get('/scriptures', [ScripturesController::class, 'getScriptures']);

    Route::get('/scriptures/favorites', [ScripturesController::class, 'getFavorites']);
    Route::patch('/scriptures/favorites/{id}', [ScripturesController::class, 'addToFavorites']);

    Route::get('/scriptures/recent', [ScripturesController::class, 'getRecent']);

    Route::get('/scriptures/{id}', [ScripturesController::class, 'getScripture']);
    Route::post('/scriptures', [ScripturesController::class, 'createScripture']);
    Route::put('/scriptures/{id}', [ScripturesController::class, 'updateScripture']);
    Route::delete('/scriptures/{id}', [ScripturesController::class, 'deleteScripture']);
});
