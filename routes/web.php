<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function(){

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/book', [App\Http\Controllers\BookController::class, 'index']);

    Route::get('/book/{apiId}', [App\Http\Controllers\BookController::class, 'show']);
    Route::post('/book', [App\Http\Controllers\BookController::class, 'store']);
    Route::get('/book/search/{searchTxt}', [App\Http\Controllers\BookController::class, 'search']);
});
