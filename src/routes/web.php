<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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


Route::get('/', [ItemController::class, 'index'])->name('item.index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');

  Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');

  Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

  Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('item.purchase.view');

  Route::post('/purchase/{item_id}', [ItemController::class, 'purchaseStore'])->name('item.purchase');

  Route::get('/purchase/address/{item_id}', [ItemController::class, 'address'])->name('item.address.edit');

  Route::post('/purchase/address/{item_id}', [ItemController::class, 'addressUpdate'])->name('item.address.update');

  Route::get('/sell', [ItemController::class, 'create'])->name('item.create');

  Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

  Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('like.store');

  Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');

  Route::get('/purchase/success/{item_id}', [ItemController::class, 'purchaseSuccess'])->name('item.purchase.success');
});

Route::get('/register', [RegisterController::class, 'create'])->name('register');

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'create'])->name('login');

Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/email/verify', function () {
  return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();

  return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
