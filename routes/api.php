<?php

use App\Http\Controllers\AdminController;
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
Route::post('/AddRole',[PermissionController::class,'AddRole']);
Route::post('/EditTeacherInfo',[AdminController::class,'EditTeacherInfo']);
Route::post('/EditReceptionInfo',[AdminController::class,'EditReceptionInfo']);
Route::post('/EditHrnInfo',[AdminController::class,'EditHrInfo']);
Route::post('/UploadImage',[AdminController::class,'UploadImage']);



});
Route::post('/GuestAnswers',[\App\Http\Controllers\GuestController::class,'storeAnswers']);
Route::post('/CreateGuest',[\App\Http\Controllers\RegisterController::class,'GuestVertification']);
Route::post('/verify',[\App\Http\Controllers\RegisterController::class,'verifyEmail']);
Route::get('/test',[\App\Http\Controllers\RegisterController::class,'test']);
Route::post('/uploadCv',[\App\Http\Controllers\GuestController::class,'uploadCv']);
Route::get('/geteacher',[\App\Http\Controllers\GuestController::class,'getTeacher']);
Route::get('/getImage',[\App\Http\Controllers\GuestController::class,'getImage']);
Route::get('/getAdvertisment',[\App\Http\Controllers\GuestController::class,'getAddvertisment']);




Route::group(['prefix' => 'reception', 'middleware' => ['role:Reception']], function() {
Route::post('/CreatStudent',[\App\Http\Controllers\RegisterController::class,'RegisterStudent']);
Route::post('/CreatClass',[\App\Http\Controllers\ReceptionController::class,'OpenClass']);
Route::post('/CreatCourse',[\App\Http\Controllers\ReceptionController::class,'OpenCourse']);
Route::post('/EditCourse',[\App\Http\Controllers\ReceptionController::class,'EditClass']);
Route::post('/DeleteCourse',[\App\Http\Controllers\ReceptionController::class,'DeleteCourse']);
Route::post('/DeleteClass',[\App\Http\Controllers\ReceptionController::class,'DeleteClass']);
Route::post('/EditCourse',[\App\Http\Controllers\ReceptionController::class,'EditCourse']);
Route::post('/EditStudentInfo',[\App\Http\Controllers\ReceptionController::class,'EditStudentInfo']);
Route::post('/AddAdvertisment',[\App\Http\Controllers\ReceptionController::class,'AddAdvertisment']);
Route::post('/EditAdvertisment',[\App\Http\Controllers\ReceptionController::class,'EditAdvertisment']);
Route::post('/DeleteAdvertisment',[\App\Http\Controllers\ReceptionController::class,'DeleteAdvertisment']);
Route::post('/ScheduleClass',[\App\Http\Controllers\ReceptionController::class,'ScheduleClass']);
Route::post('/ScheduleTeacher',[\App\Http\Controllers\ReceptionController::class,'ScheduleTeacher']);
Route::post('/EditScheduleClass',[\App\Http\Controllers\ReceptionController::class,'EditScheduleClass']);
Route::post('/EditScheduleTeacher',[\App\Http\Controllers\ReceptionController::class,'EditScheduleTeacher']);
Route::delete('/DeleteScheduleClass',[\App\Http\Controllers\ReceptionController::class,'DeleteScheduleClass']);
Route::delete('/DeleteScheduleTeacher',[\App\Http\Controllers\ReceptionController::class,'DeleteScheduleTeacher']);









});
Route::group(['prefix' => 'student', 'middleware' => ['role:Student']], function() {
Route::post('/Attendence',[\App\Http\Controllers\StudentController::class,'scan']);
Route::post('/rate',[\App\Http\Controllers\StudentController::class,'rate']);
Route::get('/viewprofile',[\App\Http\Controllers\StudentController::class,'viewProfileStudent']);
Route::get('/viewnotification',[\App\Http\Controllers\StudentController::class,'viewNotification']);
Route::post('/editprofile',[\App\Http\Controllers\StudentController::class,'editProfile']);





});
Route::group(['prefix' => 'teacher', 'middleware' => ['role:Teacher']], function() {
    Route::post('/AddType',[\App\Http\Controllers\TeacherController::class,'AddType']);
    Route::post('/AddQuestion',[\App\Http\Controllers\TeacherController::class,'AddQuestion']);
    Route::post('/DeleteQuestion',[\App\Http\Controllers\TeacherController::class,'DeleteQuestion']);
    Route::post('/MakeTest',[\App\Http\Controllers\TeacherController::class,'MakeTest']);
    Route::post('/AddQuestionExistTest',[\App\Http\Controllers\TeacherController::class,'AddQuestionExistTest']);
    Route::get('/rand',[\App\Http\Controllers\TeacherController::class,'getrand']);
    Route::get('/viewprofile',[\App\Http\Controllers\TeacherController::class,'viewProfileTeacher']);
    Route::post('/editprofile',[\App\Http\Controllers\TeacherController::class,'editProfile']);
    

    

});

Route::post('/ResentVerificationCode',[\App\Http\Controllers\RegisterController::class,'resent_code']);
Route::post('/result',[\App\Http\Controllers\ResultConrtoller::class,'calcResult']);
