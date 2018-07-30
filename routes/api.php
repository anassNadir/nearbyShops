<?php

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
Route::group(['namespace' => 'API'], function () {
    Route::group(['middleware' => ['api']], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::post('signIn', 'AuthController@authenticate');
            Route::post('signUp', 'AuthController@register');
            Route::group(['middleware' => 'jwt.auth'], function () {
                Route::post('logout', 'AuthController@logout');
            });
            Route::get('auth/refreshToken', 'AuthController@refreshedToken');
        });
        Route::group(['prefix' => 'shops'], function () {
            Route::get('nearbyShops', 'ShopController@getNearbyShops');
            Route::post('likeShop', 'ShopController@attachShop');
            Route::post('dislikeShop', 'ShopController@dislikeShop');
            Route::get('preferredShops', 'ShopController@getPreferredShops');
            Route::post('separateShop', 'ShopController@detachShop');
        });
    });
});
