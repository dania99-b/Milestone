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
use App\Http\Controllers\AstartSolutionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EducationFileController;
use App\Http\Controllers\FiltersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticController;
use App\Http\Requests\HomeworkRequest;
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
    Route::post('refreshh', [AuthController::class,'refreshh']);
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
    Route::post('/EditInfo',[InformationController::class,'editInfo']);
    Route::post('/Add/information',[InformationController::class,'store']);
    Route::get('/HR',[AdminController::class,'allHR']);
    Route::get('/teachers',[AdminController::class,'allTeachers']);
    Route::get('/receptions',[AdminController::class,'allReceptions']);
    Route::get('/get/studentNumber',[StatisticController::class,'getStudentNumber']);
    Route::get('/get/teacherNumber',[StatisticController::class,'getTeacherNumber']);
    Route::get('/get/EmployeeNumber',[StatisticController::class,'getEmployeeNumber']);
    Route::get('/get/ActiveCourseNumber',[StatisticController::class,'getActiveCourseNumber']);
   
    Route::get('/get/rateRequest/InEachCourse',[StatisticController::class,'getRateRequestInEachCourse']);
    Route::delete('/delete/teacher/{id}',[RegisterController::class,'deleteTeacher']);
    Route::delete('/delete/reception/{id}',[RegisterController::class,'deleteReception']);
    Route::delete('/delete/Hr/{id}',[RegisterController::class,'deletehr']);
    Route::get('/get/logFile',[AdminController::class,'getLogFile']);
    Route::get('/get/teacher/countRate',[StatisticController::class,'GetCountRates']);
    Route::get('/get/userLogFile/{email}',[AdminController::class,'searchInLogFile']);
    Route::post('/add/RoleToUser/{userId}/{roleId}',[AdminController::class,'addRoleToUser']);
    Route::get('/get/role',[PermissionController::class,'roles']);
    Route::delete('/delete/role/{id}',[PermissionController::class,'deleteRole']);
    Route::post('/register/subAdmin',[RegisterController::class,'subAdmin']);
    
    
    

});

Route::group(['prefix' => 'reception', 'middleware' => ['role:Reception']], function() {
    Route::post('/register/student/{id}',[RegisterController::class,'student']);
    Route::post('/EditStudentInfo',[ReceptionController::class,'EditStudentInfo']);

    Route::get('/classes',[ClassController::class,'list']);
    Route::post('/classes',[ClassController::class,'create']);
    Route::delete('/classes/{id}',[ClassController::class,'delete']);
    Route::post('/classes/update/{id}',[ClassController::class,'update']);

    Route::get('/courses',[CourseController::class,'list']);
    Route::post('/courses',[CourseController::class,'create']);
    Route::delete('/courses/{id}',[CourseController::class,'delete']);
    Route::post('/courses/update/{id}',[CourseController::class,'update']);
    
    Route::get('/advertisments',[AdvertismentController::class,'list']);
    Route::post('/advertisments',[AdvertismentController::class,'create']);
    Route::post('/advertisments/update/{id}',[AdvertismentController::class,'update']);
    Route::delete('/advertisements/{id}',[AdvertismentController::class,'delete']);

    Route::get('/ads/types',[AdvertismentTypeController::class,'list']);
    Route::post('/ads/types',[AdvertismentTypeController::class,'create']);
    Route::post('/ads/types/update',[AdvertismentTypeController::class,'update']);
    Route::delete('/ads/types',[AdvertismentTypeController::class,'delete']);
    Route::post('/approve/reservation/{id}',[ReservationConrtoller::class,'approveReservation']);
    Route::post('/get/classById',[ClassController::class,'getClassById']);
    Route::get('/get/allReservation',[ReservationConrtoller::class,'getAllReservation']);
    Route::get('/get/activeAdvertisments',[AdvertismentController::class,'getActiveAds']);
    Route::get('/get/teachers',[TeacherController::class,'list']);
    Route::get('/get/receptions',[ReceptionController::class,'list']);
    Route::get('/get/allGuests',[RegisterController::class,'getAllGuest']);
    Route::post('/get/guests/byEmail/{email}',[RegisterController::class,'searchGuestByEmail']);
    Route::post('/upload/Leave',[TeacherController::class,'uploadLeave']);
    Route::post('/upload/Resignation',[TeacherController::class,'uploadResignation']);
    Route::post('/add/fileType',[EducationFileController::class,'createFileTypes']);
    Route::post('/upload/educationFile',[EducationFileController::class,'uploadEducationFile']);
    Route::post('/delete/LeaveOrResignation/{id}',[TeacherController::class,'deleteLeave']);
    Route::delete('/delete/EducationFile/{id}',[EducationFileController::class,'deleteEducationFile']);
    
    
});

Route::get('/find/guest/{email}',[RegisterController::class,'currentGuest']);

Route::group(['prefix' => 'student', 'middleware' => ['role:Student']], function() {
    Route::post('/Attendence',[StudentController::class,'scan']);
    Route::post('/rate',[StudentController::class,'rate']);
    Route::get('/viewprofile',[StudentController::class,'viewProfileStudent']);
    Route::get('/viewnotification',[StudentController::class,'viewNotification']);
    Route::post('/editprofile',[StudentController::class,'editProfile']);
    Route::post('/check11',[ReservationConrtoller::class,'makeReservation']);
    Route::post('/storAnswer',[StudentPlacementController::class,'storeStudentAnswers']);
    Route::post('/submit',[StudentController::class,'storeAnswers']);
    Route::post('/get/advertismentById',[AdvertismentController::class,'getAdvertismentById']);
    Route::get('/get/attendenceDays',[StudentController::class,'getAttendenceDays']);
    Route::get('/get/courseHomework',[StudentController::class,'getHomeworkCurrCourse']);
    Route::get('/get/allMarks',[StudentController::class,'getAllMarks']);
    Route::post('/get/educationFile',[EducationFileController::class,'getEducationFile']);
    Route::get('/get/courseName',[CourseController::class,'getCourseseRequest']);
    Route::post('/get/Advertisment/ByType',[AdvertismentController::class,'getAdvertismentByType']);
    Route::post('/delete/Notification',[StudentController::class,'deleteNotification']);
    Route::get('/get/Notification',[StudentController::class,'getNotification']);
    

   
});

Route::group(['prefix' => 'teacher', 'middleware' => ['role:Teacher']], function() {
    Route::post('/generate',[PlacementController::class,'generate']);
    Route::post('/AddType',[QuestionTypeController::class,'AddType']);
    Route::get('/typesQuestion',[QuestionTypeController::class,'list']);
    Route::post('/AddQuestion',[QuestionController::class,'AddQuestion']);
    Route::delete('/DeleteQuestion/{question_id}',[QuestionController::class,'delete']);
    Route::post('/AddQuestionExistTest',[TeacherController::class,'AddQuestionExistTest']);
    Route::get('/rand',[TeacherController::class,'getrand']);
    Route::get('/viewprofile',[TeacherController::class,'viewProfileTeacher']);
    Route::post('/editprofile',[TeacherController::class,'editProfile']);
    Route::post('/get/questionByType/{typeID}',[FiltersController::class,'getQuestionByType']);
    Route::post('/get/questionById',[QuestionController::class,'getQuestionById']);
    Route::post('/upload/Homework',[TeacherController::class,'uploadHomework']);
    Route::post('/upload/Leave',[TeacherController::class,'uploadLeave']);
    Route::post('/delete/LeaveOrResignation/{id}',[TeacherController::class,'deleteLeave']);
    Route::post('/upload/StudentResult',[ResultController::class,'uploadStudentResult']);
    Route::post('/upload/Resignation',[TeacherController::class,'uploadResignation']);
    Route::get('/get/Requests',[TeacherController::class,'getRequest']);
    Route::get('/get/courseStudent/{course_id}',[TeacherController::class,'getCourseStudent']);
    Route::get('/get/courseTeacher',[TeacherController::class,'getTeacheCourse']);
    Route::get('/get/activeCourses',[TeacherController::class,'getActiveCourse']);
    Route::get('/get/StudentResult/{student_id}',[ResultController::class,'getStudentResultById']);
    Route::get('/get/EducationFile/CoursenameById/{courseName_id}',[CourseController::class,'getCourseNameEducationFile']);
    Route::post('/get/Attendence',[CourseController::class,'get_attendence']);
    Route::post('/send/ZoomNotification',[TeacherController::class,'sendZoomNotification']);
    Route::post('/get/FileById',[EducationFileController::class,'getEducationFileById']);
    Route::get('/get/Course/ById/{id}',[CourseController::class,'getCourseById']);
    Route::get('/get/courseAttendence/{id}',[TeacherController::class,'getAttendence']);
    Route::get('/get/ActiveCourse/forTeacher',[TeacherController::class,'getActiveCourseByTeacherId']);
    Route::get('/get/Notification',[StudentController::class,'getNotification']);
    
   
    
    

});

Route::group(['prefix' => 'hr', 'middleware' => ['role:HR']], function() {
    Route::get('/get/AllLeaves',[TeacherController::class,'getAllLeave']);
    Route::get('/get/RequestById/{id}',[HrController::class,'getRequestById']);
    Route::get('/accept/Request/{id}',[HrController::class,'approveRequest']);
    Route::post('/refuse/Request/{id}',[HrController::class,'refuseRequest']);
   
   
    

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
Route::get('/CheckBeforeReservation',[ReservationConrtoller::class,'CheckBeforeReservation']);
Route::get('/MakeReservation',[ReservationConrtoller::class,'makeReservation']);
Route::get('/get/nformation',[InformationController::class,'getInfo']);
Route::post('/test',[ReceptionController::class,'tranferGuestToStudent']);
Route::get('/get/CoursesName',[CourseController::class,'getAllCourseName']);
Route::get('/countries',[StudentController::class,'countries']);
Route::get('/days',[ReceptionController::class,'days']);


Route::get('/valid/{course}/{class}/{day}/{period}/{teacher}', [AstartSolutionController::class,'validSolution']);
Route::get('/count/{id}', [AstartSolutionController::class,'calcStudentNumbers']);
Route::get('/ddd', [AstartSolutionController::class,'getPossibleSolution']);
Route::get('/search', [SearchController::class,'search']);
Route::get('/getPeriod', [CourseController::class,'getperiod']);
Route::get('/te', [EducationFileController::class,'test1']);
