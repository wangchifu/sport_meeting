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
Route::get('callback', 'LoginController@cloudschool_back')->name('cloudschool_back');
//登出
Route::post('logout', 'LoginController@logout')->name('logout');

//錯誤
Route::get('class_teacher_error', 'HomeController@class_teacher_error')->name('class_teacher_error');

Route::group(['middleware' => 'school_admin'],function(){
    //api匯入
    Route::get('school_admins/api', 'SchoolAdminController@api')->name('school_admins.api');
    Route::get('school_admins/api_teach', 'SchoolAdminController@api_teach')->name('school_admins.api_teach');
    Route::get('school_admins/api_pull', 'SchoolAdminController@api_pull')->name('school_admins.api_pull');
    Route::get('school_admins/{semester}/student_class/{select_class_id?}', 'SchoolAdminController@student_class')->name('school_admins.student_class');
    Route::post('school_admins/api/store', 'SchoolAdminController@api_store')->name('school_admins.api_store');
    Route::delete('school_admins/api/destroy/{school_api}', 'SchoolAdminController@api_destroy')->name('school_admins.api_destroy');

    Route::get('school_admins/import', 'SchoolAdminController@import')->name('school_admins.import');
    Route::get('school_admins/student_disable/{student}', 'SchoolAdminController@student_disable')->name('school_admins.student_disable');
    Route::get('school_admins/student_create/{student_class}', 'SchoolAdminController@student_create')->name('school_admins.student_create');
    Route::get('school_admins/student_edit/{student}', 'SchoolAdminController@student_edit')->name('school_admins.student_edit');
    Route::post('school_admins/student_store', 'SchoolAdminController@student_store')->name('school_admins.student_store');
    Route::post('school_admins/student_update/{student}', 'SchoolAdminController@student_update')->name('school_admins.student_update');
    Route::post('school_admins/do_import', 'SchoolAdminController@do_import')->name('school_admins.do_import');

    //帳號管理
    Route::get('school_admins/account', 'SchoolAdminController@account')->name('school_admins.account');
    Route::get('school_admins/account_not', 'SchoolAdminController@account_not')->name('school_admins.account_not');
    Route::get('school_admins/{user}/account/set1', 'SchoolAdminController@account_set1')->name('school_admins.account_set1');
    Route::get('school_admins/{user}/account/set2', 'SchoolAdminController@account_set2')->name('school_admins.account_set2');
    Route::get('school_admins/{user}/account/disable', 'SchoolAdminController@account_disable')->name('school_admins.account_disable');
    Route::get('school_admins/{user}/account/enable', 'SchoolAdminController@account_enable')->name('school_admins.account_enable');
    Route::get('school_admins/{user}/account/remove_power', 'SchoolAdminController@account_remove_power')->name('school_admins.account_remove_power');
    Route::get('school_admins/impersonate/{user}', 'SchoolAdminController@impersonate')->name('school_admins.impersonate');
    Route::get('school_admins/item/{action_id?}', 'SchoolAdminController@item')->name('school_admins.item');
    Route::get('school_admins/item/{action}/create', 'SchoolAdminController@item_create')->name('school_admins.item_create');
    Route::post('school_admins/item/add', 'SchoolAdminController@item_add')->name('school_admins.item_add');
    Route::post('school_admins/item/import', 'SchoolAdminController@item_import')->name('school_admins.item_import');
    Route::get('school_admins/item/{item}/edit', 'SchoolAdminController@item_edit')->name('school_admins.item_edit');
    Route::patch('school_admins/item/{item}/update', 'SchoolAdminController@item_update')->name('school_admins.item_update');
    Route::get('school_admins/item/{item}/delete', 'SchoolAdminController@item_delete')->name('school_admins.item_delete');
    Route::get('school_admins/item/{item}/destroy', 'SchoolAdminController@item_destroy')->name('school_admins.item_destroy');
    Route::get('school_admins/item/{item}/enable', 'SchoolAdminController@item_enable')->name('school_admins.item_enable');

    Route::get('school_admins/action', 'SchoolAdminController@action')->name('school_admins.action');
    Route::get('school_admins/{action}/action_destroy', 'SchoolAdminController@action_destroy')->name('school_admins.action_destroy');
    Route::get('school_admins/{action}/action_show', 'SchoolAdminController@action_show')->name('school_admins.action_show');
    Route::get('school_admins/{action}/action_set_number', 'SchoolAdminController@action_set_number')->name('school_admins.action_set_number');
    Route::get('school_admins/{action}/action_set_number_null', 'SchoolAdminController@action_set_number_null')->name('school_admins.action_set_number_null');
    Route::get('school_admins/action/create', 'SchoolAdminController@action_create')->name('school_admins.action_create');
    Route::post('school_admins/action/add', 'SchoolAdminController@action_add')->name('school_admins.action_add');
    Route::get('school_admins/action/{action}/edit', 'SchoolAdminController@action_edit')->name('school_admins.action_edit');
    Route::patch('school_admins/action/{action}/update', 'SchoolAdminController@action_update')->name('school_admins.action_update');
    Route::get('school_admins/action/{action}/delete', 'SchoolAdminController@action_delete')->name('school_admins.action_delete');
    Route::get('school_admins/action/{action}/enable', 'SchoolAdminController@action_enable')->name('school_admins.action_enable');

    Route::get('school_admins/students/{action_id?}', 'SchoolAdminController@students')->name('school_admins.students');
    Route::get('school_admins/records/{action_id?}', 'SchoolAdminController@records')->name('school_admins.records');
    Route::get('school_admins/download_records/{action}', 'SchoolAdminController@download_records')->name('school_admins.download_records');
    Route::get('school_admins/scores/{action_id?}', 'SchoolAdminController@scores')->name('school_admins.scores');
    Route::get('school_admins/all_scores/{action_id?}', 'SchoolAdminController@all_scores')->name('school_admins.all_scores');
    Route::get('school_admins/total_scores/{action_id?}', 'SchoolAdminController@total_scores')->name('school_admins.total_scores');
});

Route::group(['middleware' => 'school_score'],function(){
    Route::get('school_scores/score_input/{action_id?}', 'SchoolScoreController@score_input')->name('school_scores.score_input');
    Route::match(['post','get'],'school_scores/score_input_do', 'SchoolScoreController@score_input_do')->name('school_scores.score_input_do');
    Route::get('school_scores/score_input/{action}/print/{item}/{year}/{sex}', 'SchoolScoreController@score_input_print')->name('school_scores.score_input_print');
    Route::get('school_scores/score_input2/{action}/print/{item}/{year}/{sex}', 'SchoolScoreController@score_input_print2')->name('school_scores.score_input_print2');
    Route::post('school_scores/score_input_update', 'SchoolScoreController@score_input_update')->name('school_scores.score_input_update');
    Route::get('school_scores/score_print/{action_id?}', 'SchoolScoreController@score_print')->name('school_scores.score_print');
    Route::post('school_scores/print_extra', 'SchoolScoreController@print_extra')->name('school_scores.print_extra');
    Route::post('school_scores/demo_upload', 'SchoolScoreController@demo_upload')->name('school_scores.demo_upload');
});

//登入的使用者可用
Route::group(['middleware' => 'auth'],function(){
    //Route::get('sims/impersonate_leave', 'SimulationController@impersonate_leave')->name('sims.impersonate_leave');
    Route::get('school_admins/impersonate_leave', 'SchoolAdminController@impersonate_leave')->name('school_admins.impersonate_leave');
    Route::get('class_teachers/sign_up', 'ClassTeacherController@sign_up')->name('class_teachers.sign_up');
    Route::get('class_teachers/{action}/sign_up_do', 'ClassTeacherController@sign_up_do')->name('class_teachers.sign_up_do');
    Route::post('class_teachers/sign_up_add', 'ClassTeacherController@sign_up_add')->name('class_teachers.sign_up_add');
    Route::get('class_teachers/{action}/sign_up_show', 'ClassTeacherController@sign_up_show')->name('class_teachers.sign_up_show');
    Route::get('class_teachers/{student_sign}/sign_up_delete', 'ClassTeacherController@sign_up_delete')->name('class_teachers.sign_up_delete');
    Route::patch('class_teachers/student_sign_update', 'ClassTeacherController@student_sign_update')->name('class_teachers.student_sign_update');
    Route::post('class_teachers/student_sign_make', 'ClassTeacherController@student_sign_make')->name('class_teachers.student_sign_make');
    Route::get('show/{action_id?}', 'HomeController@show')->name('show');
});

Route::group(['middleware' => 'admin'],function() {
    //模擬登入
    Route::get('sims/{user}/impersonate', 'SimulationController@impersonate')->name('sims.impersonate');
    Route::get('users', 'HomeController@users')->name('users');
    Route::post('search', 'HomeController@search')->name('search');
    Route::post('search_school', 'HomeController@search_school')->name('search_school');
});
Route::get('all', 'HomeController@all')->name('all');
Route::post('show_one', 'HomeController@show_one')->name('show_one');
