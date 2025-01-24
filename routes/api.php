<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->resource('products', ProductController::class);
Route::middleware('auth:sanctum')->post('products/{id}/categories', [ProductController::class, 'assignCategories']);
Route::middleware('auth:sanctum')->resource('categories', CategoryController::class);
