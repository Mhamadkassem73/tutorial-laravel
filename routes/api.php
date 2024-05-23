<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\User\SendEmailController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Zoom\ZoomController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/testFunction', [testController::class, 'testFunction']);
Route::post('/signUp', [UserController::class, 'signUp']);
Route::post('/selectAnswersByuser', [QuestionController::class, 'selectAnswersByuser']);
Route::post('/fetchUser', [UserController::class, 'fetchUser'])->middleware(['jwt.verify']);
Route::post('/addUser', [UserController::class, 'addUser'])->middleware(['jwt.verify']);
Route::put('/updateUser/{id?}', [UserController::class, 'updateUser'])->middleware(['jwt.verify']);

Route::post('/signIn', [UserController::class, 'signIn']);


Route::get('/fetchLessons', [QuestionController::class, 'fetchLessons'])->middleware(['jwt.verify']);
Route::get('/fetchFirstQuestion', [QuestionController::class, 'fetchFirstQuestion'])->middleware(['jwt.verify']);
Route::post('/fetchNextQuestionById/{questionId}', [QuestionController::class, 'fetchNextQuestionById'])->middleware(['jwt.verify']);

Route::get('/createNewZoomMeeting', [ZoomController::class, 'createNewZoomMeeting'])->middleware(['jwt.verify']);
Route::get('/fetchZoomById', [ZoomController::class, 'fetchZoomById'])->middleware(['jwt.verify']);
Route::get('/getAllMeetings', [ZoomController::class, 'getAllMeetings'])->middleware(['jwt.verify']);


Route::post('/sendEmail', [SendEmailController::class, 'sendEmail']);
Route::post('/forgotPassEmail', [SendEmailController::class, 'forgotPassEmail']);
Route::post('/forgotPass', [SendEmailController::class, 'forgotPass']);

 