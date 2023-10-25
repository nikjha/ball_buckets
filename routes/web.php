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
use App\Http\Controllers\BucketController;
use App\Http\Controllers\BallController;


Route::get('/buckets', [BucketController::class, 'index']);
Route::post('/buckets_store', [BucketController::class, 'store'])->name('buckets.store');
Route::post('/ball_buy', [BucketController::class, 'ball_buy'])->name('buckets.ball_buy');
Route::post('/balls', [BallController::class, 'store'])->name('balls.store');
Route::post('/buckets', [BucketController::class, 'index'])->name('buckets.index');


Route::post('/buckets/place-balls', [BucketController::class, 'placeBalls'])->name('buckets.place-balls');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [BucketController::class, 'index']);
