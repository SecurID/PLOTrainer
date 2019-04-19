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

Auth::routes();

Route::get('/createUser', 'UserController@showCreateUser')->name('showCreateUser');

Route::post('/createUser', 'UserController@createUser')->name('createUser');

Route::get('/showUsers', 'UserController@showUsers')->name('showUsers');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/createSituation', 'SituationController@showCreateSituation')->name('createSituation');

Route::post('processFiles', 'SituationController@uploadFile');

Route::get('/editSituations', 'SituationController@showEditSituation')->name('editSituation');

Route::get('/selectSituations', 'SituationController@selectSituation')->name('selectSituation');

Route::get('/randomSituations', 'SituationController@randomSituation')->name('randomSituation');

Route::get('/randomSituationsAdmin', 'SituationController@randomSituationAdmin')->name('randomSituationAdmin');

Route::get('/getHand', 'HandController@getHand')->name('getHand');

Route::get('/getHandSituations', 'HandController@getHandSituations')->name('getHandSituations');

Route::get('/getHandOptions', 'HandController@getHandOptions')->name('getHandOptions');

Route::get('/getHandIncorrect', 'HandController@getHandIncorrect')->name('getHandIncorrect');

Route::get('/deleteSituation/{id}', 'SituationController@deleteSituation')->name('deleteSituation');

Route::get('/selectSituationsTraining', 'SituationController@selectSituationTraining')->name('selectSituationsTraining');

Route::get('/showStatistics', 'StatisticsController@showStatistics')->name('showStatistics');

Route::get('/showAllStatistics', 'StatisticsController@showAllStatistics')->name('showAllStatistics');

Route::get('/showUserStatistics', 'StatisticsController@showUserStatisticsView')->name('showUserStatisticsView');

Route::get('/userStatistics/{id}', 'StatisticsController@getUserStatistic')->name('getUserStatistic');

Route::get('/renameSituation/{id}', 'SituationController@renameSituation')->name('renameSituation');

Route::get('/onlyAdminSituation/{id}', 'SituationController@onlyAdminSituation')->name('onlyAdminSituation');

Route::get('/changePosition/{id}', 'SituationController@changePositionSituation')->name('changePosition');

Route::get('/deleteUser/{id}', 'UserController@deleteUser')->name('deleteUser');

Route::get('/optionSituations', 'SituationController@optionSituations')->name('optionSituations');

Route::get('/optionSituationsTraining', 'SituationController@optionSituationsTraining')->name('optionSituationsTraining');

Route::get('/incorrectMode', 'SituationController@showIncorrectMode')->name('incorrectMode');

Route::get('/changeAdminStatus/{id}', 'UserController@changeAdminStatus')->name('changeAdminStatus');


