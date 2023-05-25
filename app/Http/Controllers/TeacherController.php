<?php

namespace App\Http\Controllers;

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
use App\Models\QuestionType;

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
}