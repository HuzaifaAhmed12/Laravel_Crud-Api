<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('categories',[App\Http\Controllers\CategoryController::class ,'index']);
Route::get('categories/create',[App\Http\Controllers\CategoryController::class, 'create']);
Route::post('categories/create', [App\Http\Controllers\CategoryController::class, 'store']);
Route::get('categories/{id}/edit',[App\Http\Controllers\CategoryController::class, 'edit']);
Route::put('categories/{id}/edit',[App\Http\Controllers\CategoryController::class, 'update']);
Route::get('categories/{id}/delete',[App\Http\Controllers\CategoryController::class, 'destroy']);

Route::get('/', function () {
    return view('frontend.welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
