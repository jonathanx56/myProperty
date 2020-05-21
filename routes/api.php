<?php

/**
 * @SWG\Swagger(schemes={"http"}, basePath="/v1", @SWG\Info(version="1.0.0", title="Api Exemplo API"))
 */

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function(){

    Route::post('/login', 'Auth\\LoginJwtController@login')->name('login');
    Route::get('/logout', 'Auth\\LoginJwtController@logout')->name('logout');
    Route::get('/refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');

    Route::get('/search', 'RealStateSearchController@index');
    Route::get('/search/{real_state_id}', 'RealStateSearchController@show');

    Route::group( ['middleware' => ['jwt.auth']], function(){

        Route::name('real-states.')->group(function(){
            Route::apiResource('real-states', 'RealStateController');
        });

        Route::name('users.')->group(function(){
            Route::apiResource('users', 'UserController');
        });

        Route::name('categories.')->group(function(){
            Route::get('categories/{id}/real-states', 'CategoryController@realStates');
            Route::apiResource('categories', 'CategoryController');
        });

        Route::name('photo.')->group(function(){
            Route::put('set-thumb/{id}/{realStateId}', 'PhotoController@setThumb');
            Route::delete('photo/{id}', 'PhotoController@delete');
        });
    });

});
