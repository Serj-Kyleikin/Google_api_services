<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    GoogleOauthController,
};
use App\Http\Controllers\API\{
    Social\SocialBindController,
    Social\Google\GoogleCalendarController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/login/google', [GoogleOauthController::class, 'getOauthLink']);
Route::get('/login/google/callback', [GoogleOauthController::class, 'handleGoogleAuthorizationCallback']);

Route::middleware('auth:sanctum', 'isUserVerified', 'isUserNotBlocked')->group( function () {

    Route::group(['prefix' => 'social'], function () {

        Route::group(['prefix' => 'bind'], function () {

            Route::get('/google', [GoogleOauthController::class, 'getOauthBindLink']);
            Route::get('/google/callback', [GoogleOauthController::class, 'handleGoogleBindCallback']);
        });

        Route::group(['prefix' => '{social_id}'], function () {

            Route::delete('', [SocialBindController::class, 'unbind']);

            Route::group(['prefix' => 'google'], function () {

                Route::post('event', [GoogleCalendarController::class, 'createEvent']);
            });
        });
    });
});