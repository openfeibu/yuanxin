<?php

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

Route::get('/', function () {
    return view('welcome');
});
// Admin  routes  for user
Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin'
], function () {
    Auth::routes();
    Route::get('password', 'UserController@getPassword');
    Route::post('password', 'UserController@postPassword');
    Route::get('locked', 'UserController@locked');
    Route::get('/', 'ResourceController@home')->name('home');
    Route::get('/dashboard', 'ResourceController@dashboard')->name('dashboard');
    Route::resource('banner', 'BannerResourceController');
    Route::post('/banner/destroyAll', 'BannerResourceController@destroyAll');


    /* 系统文章 */
    Route::resource('system_page', 'SystemPageResourceController');
    Route::post('/system_page/destroyAll', 'SystemPageResourceController@destroyAll')->name('system_page.destroy_all');

    Route::get('/setting/company', 'SettingResourceController@company')->name('setting.company.index');
    Route::post('/setting/updateCompany', 'SettingResourceController@updateCompany');
    Route::get('/setting/publicityVideo', 'SettingResourceController@publicityVideo')->name('setting.publicity_video.index');
    Route::post('/setting/updatePublicityVideo', 'SettingResourceController@updatePublicityVideo');
    Route::get('/setting/station', 'SettingResourceController@station')->name('setting.station.index');
    Route::post('/setting/updateStation', 'SettingResourceController@updateStation');

    Route::group(['prefix' => 'page','as' => 'page.','namespace' => 'Page'], function ($router) {
        Route::resource('system', 'SystemResourceController');
        Route::post('/system/destroyAll', 'SystemResourceController@destroyAll')->name('system.destroy_all');

        /*董事长致辞*/
        Route::get('/chairman', 'ChairmanResourceController@show')->name('chairman.index');
        Route::get('/chairman/show', 'ChairmanResourceController@show')->name('chairman.show');
        Route::post('/chairman/store', 'ChairmanResourceController@store')->name('chairman.store');
        Route::put('/chairman/update/{page}', 'ChairmanResourceController@update')->name('chairman.update');

        /*源心专家*/
        Route::resource('expert', 'ExpertResourceController');
        Route::post('/expert/destroyAll', 'ExpertResourceController@destroyAll')->name('expert.destroy_all');

        /*关于源心*/
        Route::resource('about', 'AboutResourceController');
        Route::post('/about/destroyAll', 'AboutResourceController@destroyAll')->name('about.destroy_all');

        /*源心专刊*/
        Route::resource('special', 'SpecialResourceController');
        Route::post('/special/destroyAll', 'SpecialResourceController@destroyAll')->name('special.destroy_all');

        /*源心精选*/
        Route::resource('feature', 'FeatureResourceController');
        Route::post('/feature/destroyAll', 'FeatureResourceController@destroyAll')->name('feature.destroy_all');

        /*生命银行*/
        Route::resource('life_bank', 'LifeBankResourceController');
        Route::post('/life_bank/destroyAll', 'LifeBankResourceController@destroyAll')->name('life_bank.destroy_all');

    });
    /* 项目列表 */
    Route::resource('project', 'ProjectResourceController');
    Route::post('/project/destroyAll', 'ProjectResourceController@destroyAll')->name('project.destroy_all');

    /* 预约列表 */
    Route::resource('appointment', 'AppointmentResourceController');
    Route::post('/appointment/destroyAll', 'AppointmentResourceController@destroyAll')->name('appointment.destroy_all');
    Route::post('/appointment/check', 'AppointmentResourceController@check')->name('appointment.check');
    Route::post('/appointment/search_code', 'AppointmentResourceController@searchCode')->name('appointment.search_code');

    /* 报告单 */
    Route::resource('report', 'ReportResourceController');
    Route::post('/report/destroyAll', 'ReportResourceController@destroyAll')->name('report.destroy_all');

    /* 报告文件 */
    Route::resource('report_file', 'ReportFileResourceController');
    Route::post('/report_file/destroyAll', 'ReportFileResourceController@destroyAll')->name('report_file.destroy_all');

    Route::group(['prefix' => 'menu'], function ($router) {
        Route::get('index', 'MenuResourceController@index');
    });

    Route::group(['prefix' => 'nav','as' => 'nav.'], function ($router) {
        Route::resource('nav', 'NavResourceController');
        Route::post('/nav/destroyAll', 'NavResourceController@destroyAll')->name('nav.destroy_all');
        Route::resource('category', 'NavCategoryResourceController');
        Route::post('/category/destroyAll', 'NavCategoryResourceController@destroyAll')->name('category.destroy_all');
    });

    Route::post('/media_folder/store', 'MediaResourceController@folderStore')->name('media_folder.store');
    Route::delete('/media_folder/destroy', 'MediaResourceController@folderDestroy')->name('media_folder.destroy');
    Route::put('/media_folder/update/{media_folder}', 'MediaResourceController@folderUpdate')->name('media_folder.update');
    Route::get('/media', 'MediaResourceController@index')->name('media.index');
    Route::put('/media/update/{media}', 'MediaResourceController@update')->name('media.update');
    Route::post('/media/upload', 'MediaResourceController@upload')->name('media.upload');
    Route::delete('/media/destroy', 'MediaResourceController@destroy')->name('media.destroy');

    Route::post('/upload/{config}/{path?}', 'UploadController@upload')->where('path', '(.*)');
    Route::post('/file/{config}/{path?}', 'UploadController@uploadFile')->where('path', '(.*)');
    Route::post('/upload_report_file/{config}/{path?}', 'UploadController@uploadReportFile')->where('path', '(.*)');

    Route::resource('user', 'UserResourceController');
    Route::post('/user/destroyAll', 'UserResourceController@destroyAll')->name('user.destroy_all');
    Route::resource('admin_user', 'AdminUserResourceController');
    Route::post('/admin_user/destroyAll', 'AdminUserResourceController@destroyAll')->name('admin_user.destroy_all');
    Route::resource('permission', 'PermissionResourceController');
    Route::post('/permission/destroyAll', 'PermissionResourceController@destroyAll')->name('permission.destroy_all');
    Route::resource('role', 'RoleResourceController');
    Route::post('/role/destroyAll', 'RoleResourceController@destroyAll')->name('role.destroy_all');
    Route::get('logout', 'Auth\LoginController@logout');


    Route::resource('news', 'NewsResourceController');
    Route::post('/news/destroyAll', 'NewsResourceController@destroyAll')->name('news.destroy_all');
    Route::post('/news/updateRecommend', 'NewsResourceController@updateRecommend')->name('news.update_recommend');

    Route::resource('link', 'LinkResourceController');
    Route::post('/link/destroyAll', 'LinkResourceController@destroyAll')->name('link.destroy_all');

    Route::resource('message', 'MessageResourceController');
    Route::post('/message/destroyAll', 'MessageResourceController@destroyAll')->name('message.destroy_all');

});
Route::group([
    'namespace' => 'Pc',
    'as' => 'pc.',
], function () {
    Route::redirect('/', '/admin', 301);
    Auth::routes();
    Route::get('/user/login','Auth\LoginController@showLoginForm');
    //Route::get('/','HomeController@home')->name('home');


    Route::get('email-verification/index','Auth\EmailVerificationController@getVerificationIndex')->name('email-verification.index');
    Route::get('email-verification/error','Auth\EmailVerificationController@getVerificationError')->name('email-verification.error');
    Route::get('email-verification/check/{token}', 'Auth\EmailVerificationController@getVerification')->name('email-verification.check');
    Route::get('email-verification-required', 'Auth\EmailVerificationController@required')->name('email-verification.required');

    Route::get('verify/send', 'Auth\LoginController@sendVerification');
    Route::get('verify/{code?}', 'Auth\LoginController@verify');

});
//Route::get('
///{slug}.html', 'PagePublicController@getPage');
/*
Route::group(
    [
        'prefix' => trans_setlocale() . '/admin/menu',
    ], function () {
    Route::post('menu/{id}/tree', 'MenuResourceController@tree');
    Route::get('menu/{id}/test', 'MenuResourceController@test');
    Route::get('menu/{id}/nested', 'MenuResourceController@nested');

    Route::resource('menu', 'MenuResourceController');
   // Route::resource('submenu', 'SubMenuResourceController');
});
*/