<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use App\Models\Answer;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Question;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\QuestionRequest;
use App\Models\LogFile;
use App\Models\QuestionType;

class TeacherController extends Controller
{
  public function AddQuestion(QuestionRequest $request)
  {
    $type_id = $request->validated()['type_id'];
    $type = QuestionType::find($type_id);
    if (!$type) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'type not found'], 400);
    }

    $question = Question::firstOrCreate([
      'text' => $request->validated()['text'],
      'level' => $request->validated()['level'],
      'type_id' => $type->id,
      'mark' => $request->validated()['mark']
    ]);
    foreach ($request->validated()['answers'] as $answerData) {
      Answer::create([
        'question_id' => $question->id,
        'name' => $answerData['name'],
        'is_true' => $answerData['is_true'],
      ]);
    }
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Add Question';
$log->save();

    return response()->json(['message' => 'Question Added Successfully'], 200);
  }
  public function DeleteQuestion(Request $request)
  {
    $question_id = $request->validated()['question_id'];
    $question = Question::find($question_id);
    if (!$question) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Question not found'], 400);
    }
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Delete Question';
$log->save();

    $question->delete();

    return response()->json(['message' => 'Question deleted Successfully'], 200);
  }

  public function AddType(Request $request)
  {
    $type = QuestionType::firstOrCreate([
      'name' => $request['name'],

    ]);
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Add Question Type';
$log->save();
return response()->json(['message' => 'Question Type Added Successfully'], 200);
  }
  public function MakeTest(TestRequest $request)
  {
    $questions = $questions = Question::with('answers')->orderBy(DB::raw('RAND()'))->take(2)->get();
    $newTest = Test::firstOrCreate([
      'start_date' => $request->validated()['start_date'],
      'end_date' => $request->validated()['end_date'],],
      [
        'questions' => json_encode($questions->pluck('id')),
    ]);



    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Create Test';
$log->save();
return response()->json(['message' => 'Test Created Successfully', json_encode($questions->pluck('id'))], 200);
  }

  public function AddQuestionExistTest(Request $request)
  {
    $test_id = $request['test_id'];
    $test = Test::find($test_id);
    if (!$test) {
      return response()->json(['message' => 'Question not found'], 400);
    }

    $questions = $request->input('question_id');
    foreach ($questions as $question) {
      $questionModel = Question::where('id', $question)->firstOrFail();
      $test->questions()->attach($questionModel);
     
    }
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Add Question To Exist Test';
$log->save();
return response()->json(['message' => 'Question Added Successfully'], 200);
  }

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
  }

