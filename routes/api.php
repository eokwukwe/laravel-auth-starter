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

Route::middleware('guest:api')->prefix('v1')->group(function () {
    Route::post('register', 'Auth\RegisterController@register');

    Route::post(
        'verification/verify/{user}',
        'Auth\VerificationController@verify'
    )->name('verification.verify');

    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');

    Route::post(
        'password/resetEmail',
        'Auth\ForgotPasswordController@sendResetLinkEmail'
    );

});
