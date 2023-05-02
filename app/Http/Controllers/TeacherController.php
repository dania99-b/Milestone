<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use App\Models\Answer;
use App\Models\Teacher;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\QuestionRequest;
use App\Models\Employee;

class TeacherController extends Controller
{
  public function AddQuestion(QuestionRequest $request)
  {
    $type_id = $request->validated()['type_id'];
    $type = Type::find($type_id);
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

    $question->delete();

    return response()->json(['message' => 'Question deleted Successfully'], 200);
  }

  public function AddType(Request $request)
  {
    $type = Type::firstOrCreate([
      'name' => $request['name'],

    ]);
  }
  public function MakeTest(TestRequest $request)
  {
    $newTest = Test::firstOrCreate([
      'start_date' => $request->validated()['start_date'],
      'end_date' => $request->validated()['end_date'],

    ]);
    $questions = $questions = Question::orderBy(DB::raw('RAND()'))->take(7)->get();
    foreach ($questions as $question) {

      $newTest->questions()->attach($question);
    }
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
      return response()->json(['message' => 'Question deleted Successfully'], 200);
    }
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
            $user->employee_image = $employee->image;
           
            return response()->json($user, 200);
        }
    }
    return response()->json(['error' => 'Teacher profile not found.'], 404);
}
}
