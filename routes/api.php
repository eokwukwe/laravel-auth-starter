<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('logout', 'Auth\LoginController@logout');
});

Route::middleware('guest:api')->prefix('v1')->group(function () {
    Route::post('register', 'Auth\RegisterController@register');

    Route::post(
        'verification/verify/{user}',
        'Auth\VerificationController@verify'
    )->name('verification.verify');

    Route::post('verification/resend', 'Auth\VerificationController@resend');

    Route::post('login', 'Auth\LoginController@login');

    Route::post(
        'forgot-password',
        'Auth\ForgotPasswordController@forgotPassword'
    )->name('password.reset');

    Route::post(
        'reset-password',
        'Auth\ResetPasswordController@resetPassword'
    )->name('password.update');
});
