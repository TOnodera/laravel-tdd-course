<?php

declare(strict_types=1);

use App\Http\Controllers\BlogViewController;
use App\Http\Controllers\MyPage\UserLoginController;
use App\Http\Controllers\SignUpController;
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

//トップページ
Route::get('/', [BlogViewController::class, 'index']);

//ブログページ
Route::get('blogs/{blog}', [BlogViewController::class, 'show']);

//ユーザー登録
Route::get('signup/', [SignUpController::class, 'index']);
Route::post('signup/', [SignUpController::class, 'store']);

//ログインページ
Route::get('mypage/login', [UserLoginController::class, 'index']);
Route::post('mypage/login', [UserLoginController::class, 'login']);
