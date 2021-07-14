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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->post('/weapp/code','App\Http\Controllers\Api\Auth\WeAppUserLoginController@code');
    $api->post('/weapp/login','App\Http\Controllers\Api\Auth\WeAppUserLoginController@login');

    $api->get('/','App\Http\Controllers\Api\HomeController@index');
    $api->get('/banners','App\Http\Controllers\Api\HomeController@getBanners');
    $api->get('/pages','App\Http\Controllers\Api\PageController@getPages');
    $api->get('/pages/{id}','App\Http\Controllers\Api\PageController@getPage');
    $api->get('/pages/slug/{slug}','App\Http\Controllers\Api\PageController@getPageSlug');
    $api->get('/page-categories','App\Http\Controllers\Api\PageCategoryController@getPageCategories');

    $api->get('/projects','App\Http\Controllers\Api\ProjectController@getProjects');
    $api->get('/projects/{id}','App\Http\Controllers\Api\ProjectController@getProject');

    $api->post('/appointments','App\Http\Controllers\Api\AppointmentController@storeAppointment');
    $api->get('/appointments','App\Http\Controllers\Api\AppointmentController@getAppointments');

});
