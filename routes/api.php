<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
    Route::get('users', [AuthController::class, 'listUsers']);
    Route::post('create-user', [AuthController::class, 'createUser']);
    /**
     * 
     */
    Route::get('category', [ApiController::class, 'listCategories']);
    Route::post('category', [ApiController::class, 'createCategory']);
    Route::put('category/{id}', [ApiController::class, 'updateCategory']);
    Route::delete('category/{id}', [ApiController::class, 'deleteCategory']);
    Route::get('category/{id}', [ApiController::class, 'getCategory']);

    /**
     * 
     */
    Route::get('movies', [ApiController::class, 'listMovies']);
    Route::post('movies', [ApiController::class, 'createMovie']);
    Route::put('movies/{id}', [ApiController::class, 'updateMovie']);
    Route::delete('movies/{id}', [ApiController::class, 'deleteMovie']);
    Route::get('movies/{id}', [ApiController::class, 'getMovie']);
    Route::get('moviesByCategory/{id}', [ApiController::class, 'moviesByCategory']);
    Route::get('filter-movies', [ApiController::class, 'filterMovies']);

});


