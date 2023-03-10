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
    $api->post('/submit_phone','App\Http\Controllers\Api\UserController@submitPhone');
    $api->get('/user_info','App\Http\Controllers\Api\UserController@getUser');
    $api->get('contact','App\Http\Controllers\Api\HomeController@contact');

    $api->get('/','App\Http\Controllers\Api\HomeController@index');
    $api->get('/test','App\Http\Controllers\Api\HomeController@test');

    $api->get('/banners','App\Http\Controllers\Api\HomeController@getBanners');
    $api->get('/settings','App\Http\Controllers\Api\HomeController@getSettings');
    $api->get('/pages','App\Http\Controllers\Api\PageController@getPages');
    $api->get('/pages/{id}','App\Http\Controllers\Api\PageController@getPage');
    $api->get('/pages/slug/{slug}','App\Http\Controllers\Api\PageController@getPageSlug');
    $api->get('/page-categories','App\Http\Controllers\Api\PageCategoryController@getPageCategories');
    $api->get('/life_banks','App\Http\Controllers\Api\PageController@getLifeBanks');
    $api->get('/about-images','App\Http\Controllers\Api\PageController@getAboutImages');
    $api->get('/about-contents','App\Http\Controllers\Api\PageController@getAboutContents');

    $api->get('/projects','App\Http\Controllers\Api\ProjectController@getProjects');
    $api->get('/projects/{id}','App\Http\Controllers\Api\ProjectController@getProject');

    $api->get('/appointments','App\Http\Controllers\Api\AppointmentController@getAppointments');
    $api->post('/appointments','App\Http\Controllers\Api\AppointmentController@storeAppointment');
    $api->get('/appointments/{id}','App\Http\Controllers\Api\AppointmentController@getAppointment');
    $api->get('/appointment_dates','App\Http\Controllers\Api\AppointmentController@getAppointmentDates');

    $api->get('/reports','App\Http\Controllers\Api\ReportController@getReports');
    $api->get('/reports/{id}','App\Http\Controllers\Api\ReportController@getReport');

    $api->post('/report_files/mails','App\Http\Controllers\Api\ReportController@sendMail');

    $api->get('/user/archive','App\Http\Controllers\Api\UserController@getArchive');
    $api->post('/user/archive','App\Http\Controllers\Api\UserController@storeArchive');
});
