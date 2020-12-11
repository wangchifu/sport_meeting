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
    return view('index');
})->name('index');

//Auth::routes();

//gsuite登入
Route::get('login', 'LoginController@login')->name('login');

Route::post('g_auth', 'LoginController@g_auth')->name('g_auth');
//openid登入
Route::get('openid_get', 'LoginController@openid_get')->name('openid_get');
//cloudschool登入
Route::post('cloudschool_auth', 'LoginController@cloudschool_auth')->name('cloudschool_auth');
Route::get('cloudschool_back', 'LoginController@cloudschool_back')->name('cloudschool_back');
//登出
Route::post('logout', 'LoginController@logout')->name('logout');

Route::group(['middleware' => 'school_admin'],function(){
    //api匯入
    Route::get('school_admins/api', 'SchoolAdminController@api')->name('school_admins.api');
    Route::post('school_admins/api/store', 'SchoolAdminController@api_store')->name('school_admins.api_store');
    Route::delete('school_admins/api/destroy/{school_api}', 'SchoolAdminController@api_destroy')->name('school_admins.api_destroy');

    //帳號管理
    Route::get('school_admins/account', 'SchoolAdminController@account')->name('school_admins.account');
    Route::get('school_admins/impersonate/{user}', 'SchoolAdminController@impersonate')->name('school_admins.impersonate');
});

//登入的使用者可用
Route::group(['middleware' => 'auth'],function(){
    Route::get('school_admins/impersonate_leave', 'SchoolAdminController@impersonate_leave')->name('school_admins.impersonate_leave');
});
