<?php

use App\Http\Controllers\PostController;
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

Route::get('/', [PostController::class, "getHouses"])->name('home');
Route::post('/sendData', [PostController::class, "sendData"])->name('sendData');
Route::get('/searchCharacter/{id}', [PostController::class, "searchCharacter"])->name('searchCharacter');
Route::get('/deleteCharacter/{id}', [PostController::class, "deleteCharacter"])->name('deleteCharacter');