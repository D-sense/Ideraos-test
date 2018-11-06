<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});







    // display countries
    Route::post('save_country', 'CountryAPIController@storeCountry');
    Route::get('get_countries', 'CountryAPIController@fetchCountries');

    // display states
    Route::post('save_state', 'CountryAPIController@storeState');
    Route::get('get_states', 'CountryAPIController@fetchStates');

    // display cities
    Route::post('save_city', 'CountryAPIController@storeCity');
    Route::post('find_cities/{country_name}', 'CountryAPIController@searchForCity');



