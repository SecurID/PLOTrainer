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

Route::post('/answer', 'AnswerController@saveAnswer')->name('saveAnswer');

Route::post('/answerIncorrect', 'AnswerController@saveAnswerIncorrect')->name('saveAnswerIncorrect');

Route::get('/processFile', 'AnswerController@processSpecificFile')->name('processSpecificFile');

Route::get('/deleteSituation/{id}', 'SituationController@deleteSituation')->name('deleteSituation');

Route::post('/renameSituation/{id}', 'SituationController@renameSituation')->name('renameSituation');
