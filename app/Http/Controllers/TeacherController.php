<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationFileRequest;
use App\Http\Requests\HomeworkRequest;
use App\Http\Requests\LeaveOrResignationRequest;
use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use App\Models\Answer;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Question;
use App\Models\LogFile;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\QuestionRequest;
use App\Models\Course;
use App\Models\EducationFile;
use App\Models\Homework;
use App\Models\LeaveAndResignation;
use App\Models\QuestionType;
use App\Models\Student;
use Carbon\Carbon;

class TeacherController extends Controller
{
//   public function AddQuestionExistTest(Request $request)
//   {
//     $test_id = $request['test_id'];
//     $test = Test::find($test_id);
//     if (!$test) {
//       return response()->json(['message' => 'Question not found'], 400);
//     }

//     $questions = $request->input('question_id');
//     foreach ($questions as $question) {
//       $questionModel = Question::where('id', $question)->firstOrFail();
//       $test->questions()->attach($questionModel);
     
//     }
//     $user=Auth::user();
//     $employee=$user->employee;
//     $log = new LogFile();
// $log->employee_id= $employee->id;
// $log->action = 'Add Question To Exist Test';
// $log->save();
// return response()->json(['message' => 'Question Added Successfully'], 200);
//   }

  public function getrand()
  {
    $questions = Question::orderBy(DB::raw('RAND()'))->take(10)->get();
    return $questions;
  }

  public function viewProfileTeacher()
  {
    $user = JWTAuth::parseToken()->authenticate();
    $employee = Employee::where('user_id', $user->id)->first();
    if ($employee) {
      $teacher = Teacher::where('employee_id', $employee->id)->first();
      if ($teacher) {
        $user->image = $employee->image;

        return response()->json($user, 200);
      }
    }
    return response()->json(['error' => 'Teacher profile not found.'], 404);
  }

  public function editProfile(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    $teacher=$employee->teacher;
   
    if (!$teacher) {
      return response()->json(['message' => 'Student not found'], 404);
  }

  if ($request->hasFile('image')) {
      $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $employee->image = $image;
  }

  $user = $employee->user;
  $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone']));

  if ($user->isDirty()) {
      $user->save();
  }

  if ($employee->isDirty()) {
      $employee->save();
  }
  $user=Auth::user();
  $employee=$user->employee;
  $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Edi Own Profile';
$log->save();
  return response()->json(['message' => 'Teacher info updated successfully'], 200);
}

public function list(){
  
  $teachers = Teacher::with('employee.user')->get();
  
  return response()->json($teachers, 200);
}

public function uploadHomework(HomeworkRequest $request){
  if ($request->hasFile('file')) {
    $file = $request->file('file');
    $filename = $file->getClientOriginalName();
    $file->move('files/', $filename);
} else {
    $filename = null;
}
  $homework=Homework::firstOrCreate([
    'course_id'=>$request->validated()['course_id'],
         'text'=>$request->validated()['text'],
         'file'=>$filename
  ]);
return response()->json(['message'=>'Homework Uploaded Successfully'],200);
}

public function uploadLeave(LeaveOrResignationRequest $request){
 
  $user = JWTAuth::parseToken()->authenticate();
  $employee = $user->employee;
  LeaveAndResignation::FirstOrCreate([
           
            'employee_id'=>$employee->id,
              'reason'=>$request->validated()['reason'],
              'from'=>Carbon::now(),
              'type'=>"Leave"

  ]);}

  public function uploadResignation(LeaveOrResignationRequest $request){
    $file = $request->file('file')->move('files/', $request->file('file')->getClientOriginalName());
    $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    LeaveAndResignation::FirstOrCreate([
      'employee_id'=>$employee->id,
      'reason'=>$request->validated()['reason'],
      'file'=>$file,
      'from'=>$request->validated()['from'],
      'to'=>$request->validated()['to'],
      'type'=>"Resignation",
      'comment'=>$request->validated()['comment']
    ]);
            }


  public function deleteLeave($id){
       
    $leave = LeaveAndResignation::find($id);
    if (!$leave) {
        return response()->json(['message' => 'Class not found'], 400);
    }
    $leave->delete();
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
    $log->employee_id= $employee->id;
    $log->action = 'Delete Leave Or Resignation';
    $log->save();
    return response()->json(['message' => 'Leave Or Resignation deleted successfully'], 200);
}
public function getAllLeave(){
  $leaves=LeaveAndResignation::with('employee.user')->get();
  return response()->json($leaves,200);
}

public function getRequest(){
  $user = JWTAuth::parseToken()->authenticate();
  $employee = $user->employee;
  $requests=LeaveAndResignation::where('employee_id',$employee->id)->get();
  return response()->json($requests,200);
}
public function getRequestById($id){
  $requests=LeaveAndResignation::find($id);
  return response()->json($requests,200);
}

public function getCourseStudent($course_id)
{

    $course = Course::find($course_id);

    if ($course) {
        $students = $course->course_result()->with('student.user')->get()->pluck('student');
        return response()->json($students, 200);
    }

    return response()->json(['message' => 'Course not found.'], 404);
}
}