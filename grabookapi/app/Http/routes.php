<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
$api = app('Dingo\Api\Routing\Router');
Route::get('{slug}/{token}', function () {
    return view('index');
});
Route::get('{slug}', function () {
    return view('index');
});
Route::get('/', function () {
    return view('index');
});
$api->version('v1', function ($api) {
    //$api->post('braintreewebhook', ['uses' => 'App\Api\v1\Controllers\DonationController@braintreeWebhook']);
    $api->post('/authenticate', ['as' => 'authenticate.post', 'uses' => 'App\Api\v1\Controllers\AuthController@authenticate']);

    $api->post('forgotpassword', 'App\Api\v1\Controllers\PasswordController@forgotPassword');
    $api->post('postResetpassword', 'App\Api\v1\Controllers\PasswordController@postResetpassword');
    $api->post('/test', ['as' => 'authenticate.test', 'uses' => 'App\Api\v1\Controllers\TestController@test']);
    $api->resource('country', 'App\Api\v1\Controllers\CountryController');
    $api->get('/phoneTypeList', 'App\Api\v1\Controllers\PhoneTypeController@phoneTypeList');
    $api->get('/emailTypeList', 'App\Api\v1\Controllers\EmailTypeController@emailTypeList');
    $api->get('/divisionarray', 'App\Api\v1\Controllers\DivisionController@getAllDivisions');
    $api->get('/commonTags', 'App\Api\v1\Controllers\IndexController@commonTags');

    $api->resource('fb', 'App\Api\v1\Controllers\FBController');


});
$api->version('v1', ['middleware' => 'JWTAuth'], function($api) {
    $api->resource('contact', 'App\Api\v1\Controllers\ContactController');
    $api->get('/owner', ['as' => 'user.owner', 'uses' => 'App\Api\v1\Controllers\UserController@getOwner']);
    $api->get('/clientlist', ['as' => 'user.client', 'uses' => 'App\Api\v1\Controllers\ClientController@getClient']);
    $api->get('/divisionlist', ['as' => 'user.division', 'uses' => 'App\Api\v1\Controllers\DivisionController@getDivision']);
    $api->get('/positionlist/{divisionId?}', ['as' => 'user.position', 'uses' => 'App\Api\v1\Controllers\ClientController@getPosition']);
    $api->get('/statelist', ['as' => 'state.list', 'uses' => 'App\Api\v1\Controllers\StateController@getStates']);
    $api->get('/industrytypelist/{divisionId?}', ['as' => 'industryType.list', 'uses' => 'App\Api\v1\Controllers\IndustryController@getIndustryType']);

    $api->get('/technologylist', ['as' => 'industryType.list', 'uses' => 'App\Api\v1\Controllers\TechnologyController@getTechnologyList']);
    $api->get('/ownershiplist', ['as' => 'ownership.list', 'uses' => 'App\Api\v1\Controllers\OwnershipController@getOwnership']);
    $api->put('changeContactStatus/{id}', 'App\Api\v1\Controllers\ContactController@changeContactStatus');

    $api->resource('client', 'App\Api\v1\Controllers\ClientController');
    $api->resource('user', 'App\Api\v1\Controllers\UserController');
    $api->resource('note', 'App\Api\v1\Controllers\NoteController');
    $api->put('changeUserStatus/{id}', 'App\Api\v1\Controllers\UserController@changeUserStatus');
    $api->post('changePassword', 'App\Api\v1\Controllers\AuthController@changePassword');
    $api->resource('rating', 'App\Api\v1\Controllers\RatingController');
    $api->resource('marketingSource', 'App\Api\v1\Controllers\MarketingSourceController');
    $api->resource('education', 'App\Api\v1\Controllers\EducationController');
    $api->resource('applicationLanguage', 'App\Api\v1\Controllers\ApplicationLanguageController');
    $api->resource('licensesType', 'App\Api\v1\Controllers\LicenseTypeController');
    $api->resource('licensesType', 'App\Api\v1\Controllers\LicenseTypeController');
    $api->resource('technology', 'App\Api\v1\Controllers\TechnologyController');
    $api->resource('position', 'App\Api\v1\Controllers\PositionController');
    $api->resource('skill', 'App\Api\v1\Controllers\SkillController');
    $api->resource('industryType', 'App\Api\v1\Controllers\IndustryTypeController');
});

