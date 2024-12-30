<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
   Route::resource('tasks', App\Http\Controllers\Api\TaskController::class);
   Route::get('/tasks/{id}/share', [App\Http\Controllers\Api\TaskController::class, 'share']);
   Route::post('/tasks/{id}/share', [App\Http\Controllers\Api\TaskController::class, 'share']);

});
