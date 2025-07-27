<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use Illuminate\Support\Facades\Auth;
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


Route::get('/', [ItemController::class, 'index']);

Route::get('/items/{itemId}', [ItemController::class, 'show']);


Route::middleware(['auth'])->group(
    function () {
        Route::get('/profile', [MypageController::class, 'createProfile']);
        Route::post('/profile', [MypageController::class, 'storeProfile']);
        Route::get('/mypage', [MypageController::class, 'index']);
        Route::get('/mypage/profile', [MypageController::class, 'editProfile']);
        Route::post('/mypage/profile', [MypageController::class, 'updateProfile']);
        Route::get('/sell', [ItemController::class, 'createSell']);
        Route::post('/sell', [ItemController::class, 'storeSell']);
        Route::get('/purchase/{item_id}', [ItemController::class, 'createPurchase'])->name('purchase.create');
        Route::post('/purchase/{item_id}', [ItemController::class, 'storePurchase'])->name('purchase.store');
        Route::get('/purchase/address/{item_id}', [MypageController::class, 'editAddress']);
    }
);
