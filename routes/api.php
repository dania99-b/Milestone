<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');   

});
Route::group(['prefix' => 'admin', 'middleware' => ['role:Admin']], function() {

Route::post('/CreateTeacher',[\App\Http\Controllers\RegisterController::class,'RegisterTeacher']);
Route::post('/CreateReception',[\App\Http\Controllers\RegisterController::class,'RegisterReception']);
Route::post('/CreateHr',[\App\Http\Controllers\RegisterController::class,'RegisterHR']);
Route::post('/CreateAdmin',[\App\Http\Controllers\RegisterController::class,'RegisterAdmin']);
});

Route::post('/AddRole',[PermissionController::class,'AddRole']);
Route::post('/CreateGuest',[\App\Http\Controllers\RegisterController::class,'GuestVertification']);
Route::post('/verify',[\App\Http\Controllers\RegisterController::class,'verifyEmail']);
Route::get('/test',[\App\Http\Controllers\RegisterController::class,'test']);

Route::group(['prefix' => 'reception', 'middleware' => ['role:Reception']], function() {
Route::post('/CreatStudent',[\App\Http\Controllers\RegisterController::class,'RegisterStudent']);
});