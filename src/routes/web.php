<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [ItemController::class, 'index'])->name('item.index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/sell', [ItemController::class, 'create'])->name('item.create');

Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('item.purchase');

Route::get('/purchase/address/{item_id}', [ItemController::class, 'address'])->name('item.address.edit');

Route::post('/purchase/address/{item_id}', [ItemController::class, 'addressUpdate'])->name('item.address.update');

Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');

Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');

Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
