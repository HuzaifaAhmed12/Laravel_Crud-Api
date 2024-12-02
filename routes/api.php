<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[UserController::class,'loginUser']);


Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::get('user',[UserController::class,'userDetails']);
    Route::get('logout',[UserController::class,'logout']);
});

Route::post('products', [ProductController::class, 'store']); 
Route::get('products/{id}', [ProductController::class, 'show']); 
Route::get('products', [ProductController::class, 'index']); 
Route::put('products/{id}', [ProductController::class, 'update']); 
Route::delete('products/{id}', [ProductController::class, 'destroy']); 

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);

Route::post('products', [ProductController::class, 'store']);

// For Fetch the Products with pagination

Route::get('/products', [ProductController::class, 'index']);

//For Categories Routes

Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
