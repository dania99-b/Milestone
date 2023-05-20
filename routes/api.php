<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReservationConrtoller;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AdvertismentController;
use App\Http\Controllers\AdvertismentTypeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTypeController;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh',  [AuthController::class,'refresh']);
    Route::post('me',  [AuthController::class,'me']);   
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:Admin']], function() {
    Route::post('/register/teacher',[RegisterController::class,'teacher']);
    Route::post('/register/reception',[RegisterController::class,'reception']);
    Route::post('/register/hr',[RegisterController::class,'HR']);
    Route::post('/register/admin',[RegisterController::class,'admin']);
    Route::post('/roles',[PermissionController::class,'addRole']);
    Route::post('/permissions',[PermissionController::class,'addPermission']);
    Route::post('/update/teacher',[AdminController::class,'updateTeacher']);
    Route::post('/update/reception',[AdminController::class,'updateReception']);
    Route::post('/update/hr',[AdminController::class,'updateHR']);
    Route::post('/UploadImage',[AdminController::class,'UploadImage']);
});

Route::group(['prefix' => 'reception', 'middleware' => ['role:Reception']], function() {
    Route::post('/register/student',[RegisterController::class,'student']);
    Route::post('/EditStudentInfo',[ReceptionController::class,'EditStudentInfo']);

    Route::get('/classes',[ClassController::class,'list']);
    Route::post('/classes',[ClassController::class,'create']);
    Route::delete('/classes',[ClassController::class,'delete']);
    Route::post('/classes/update',[ClassController::class,'update']);

    Route::get('/courses',[CourseController::class,'list']);
    Route::post('/courses',[CourseController::class,'create']);
    Route::delete('/courses',[CourseController::class,'delete']);
    Route::post('/courses/update',[CourseController::class,'update']);
    
    Route::get('/advertisments',[AdvertismentController::class,'list']);
    Route::post('/advertisments',[AdvertismentController::class,'create']);
    Route::post('/advertisments/update',[AdvertismentController::class,'update']);
    Route::delete('/advertisements',[AdvertismentController::class,'delete']);

    Route::get('/ads/types',[AdvertismentTypeController::class,'list']);
    Route::post('/ads/types',[AdvertismentTypeController::class,'create']);
    Route::post('/ads/types/update',[AdvertismentTypeController::class,'update']);
    Route::delete('/ads/types',[AdvertismentTypeController::class,'delete']);

});

Route::get('/find/guest/{email}',[RegisterController::class,'currentGuest']);

Route::group(['prefix' => 'student', 'middleware' => ['role:Student']], function() {
    Route::post('/Attendence',[StudentController::class,'scan']);
    Route::post('/rate',[StudentController::class,'rate']);
    Route::get('/viewprofile',[StudentController::class,'viewProfileStudent']);
    Route::get('/viewnotification',[StudentController::class,'viewNotification']);
    Route::post('/editprofile',[StudentController::class,'editProfile']);
    Route::get('/check11',[ReservationConrtoller::class,'CheckBeforeReservation']);
    Route::post('/storAnswer',[StudentPlacementController::class,'storeStudentAnswers']);
    Route::post('/submit',[StudentController::class,'storeAnswers']);
   
});

Route::group(['prefix' => 'teacher', 'middleware' => ['role:Teacher']], function() {
    Route::post('/generate',[PlacementController::class,'generate']);

    Route::post('/AddType',[QuestionTypeController::class,'AddType']);
    Route::post('/AddQuestion',[QuestionController::class,'AddQuestion']);
    Route::post('/DeleteQuestion',[TeacherController::class,'DeleteQuestion']);
    Route::post('/AddQuestionExistTest',[TeacherController::class,'AddQuestionExistTest']);
    Route::get('/rand',[TeacherController::class,'getrand']);
    Route::get('/viewprofile',[TeacherController::class,'viewProfileTeacher']);
    Route::post('/editprofile',[TeacherController::class,'editProfile']);
});

Route::post('/register/guest',[RegisterController::class,'guestVertification']);
Route::post('/verify/email',[RegisterController::class,'verify']);
Route::post('/resend/code',[RegisterController::class,'resend']);
Route::get('/teachers',[GuestController::class,'teachersList']);
Route::get('/images',[GuestController::class,'imagesList']);
Route::get('/advertisements',[GuestController::class,'advertisementsList']);
Route::post('/GuestAnswers',[GuestController::class,'storeAnswers']);
Route::get('/test',[RegisterController::class,'test']);
Route::post('/result',[ResultConrtoller::class,'calcResult']);
Route::post('/uploadCv',[GuestController::class,'uploadCv']);
Route::post('/submit',[GuestController::class,'storeAnswers']);
Route::get('/getTest',[PlacementController::class,'getTest']);
Route::get('/getTest',[PlacementController::class,'getTest']);
Route::get('/CheckBeforeReservation',[ReservationConrtoller::class,'CheckBeforeReservation']);

