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

//錯誤
Route::get('class_teacher_error', 'HomeController@class_teacher_error')->name('class_teacher_error');

Route::group(['middleware' => 'school_admin'],function(){
    //api匯入
    Route::get('school_admins/api', 'SchoolAdminController@api')->name('school_admins.api');
    Route::get('school_admins/api_pull', 'SchoolAdminController@api_pull')->name('school_admins.api_pull');
    Route::get('school_admins/{semester}/student_class/{select_class_id?}', 'SchoolAdminController@student_class')->name('school_admins.student_class');
    Route::post('school_admins/api/store', 'SchoolAdminController@api_store')->name('school_admins.api_store');
    Route::delete('school_admins/api/destroy/{school_api}', 'SchoolAdminController@api_destroy')->name('school_admins.api_destroy');

    //帳號管理
    Route::get('school_admins/account', 'SchoolAdminController@account')->name('school_admins.account');
    Route::get('school_admins/{user}/account/set1', 'SchoolAdminController@account_set1')->name('school_admins.account_set1');
    Route::get('school_admins/{user}/account/set2', 'SchoolAdminController@account_set2')->name('school_admins.account_set2');
    Route::get('school_admins/{user}/account/disable', 'SchoolAdminController@account_disable')->name('school_admins.account_disable');
    Route::get('school_admins/{user}/account/enable', 'SchoolAdminController@account_enable')->name('school_admins.account_enable');
    Route::get('school_admins/{user}/account/remove_power', 'SchoolAdminController@account_remove_power')->name('school_admins.account_remove_power');
    Route::get('school_admins/impersonate/{user}', 'SchoolAdminController@impersonate')->name('school_admins.impersonate');
    Route::get('school_admins/item', 'SchoolAdminController@item')->name('school_admins.item');
    Route::get('school_admins/item/create', 'SchoolAdminController@item_create')->name('school_admins.item_create');
    Route::post('school_admins/item/add', 'SchoolAdminController@item_add')->name('school_admins.item_add');
    Route::get('school_admins/item/{item}/edit', 'SchoolAdminController@item_edit')->name('school_admins.item_edit');
    Route::patch('school_admins/item/{item}/update', 'SchoolAdminController@item_update')->name('school_admins.item_update');
    Route::get('school_admins/item/{item}/delete', 'SchoolAdminController@item_delete')->name('school_admins.item_delete');
    Route::get('school_admins/item/{item}/enable', 'SchoolAdminController@item_enable')->name('school_admins.item_enable');

    Route::get('school_admins/action', 'SchoolAdminController@action')->name('school_admins.action');
    Route::get('school_admins/action/create', 'SchoolAdminController@action_create')->name('school_admins.action_create');
    Route::post('school_admins/action/add', 'SchoolAdminController@action_add')->name('school_admins.action_add');
    Route::get('school_admins/action/{action}/edit', 'SchoolAdminController@action_edit')->name('school_admins.action_edit');
    Route::patch('school_admins/action/{action}/update', 'SchoolAdminController@action_update')->name('school_admins.action_update');
    Route::get('school_admins/action/{action}/delete', 'SchoolAdminController@action_delete')->name('school_admins.action_delete');
    Route::get('school_admins/action/{action}/enable', 'SchoolAdminController@action_enable')->name('school_admins.action_enable');
});

//登入的使用者可用
Route::group(['middleware' => 'auth'],function(){
    Route::get('school_admins/impersonate_leave', 'SchoolAdminController@impersonate_leave')->name('school_admins.impersonate_leave');

    Route::get('class_teachers/sign_up', 'ClassTeacherController@sign_up')->name('class_teachers.sign_up');
});
