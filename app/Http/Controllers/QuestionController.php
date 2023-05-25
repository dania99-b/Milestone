<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\LogFile;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\QuestionRequest;


class QuestionController extends Controller
{
    public function list(){
        $questions = Question::all();
        return response()->json($questions, 200);
    }

    public function AddQuestion(QuestionRequest $request){
      $type_id = $request->validated()['type_id'];
      $type = QuestionType::find($type_id);
      if (!$type) {
        return response()->json(['message' => 'Type not found'], 404);
      }
      $question = Question::firstOrCreate([
        'text' => $request->validated()['text'],
        'level' => $request->validated()['level'],
        'mark' => $request->validated()['mark'],
        'type_id' => $type->id,
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
        $log->action = 'Add new question';
        $log->save();
      return response()->json(['message' => 'Question Added Successfully'], 200);
    }

    public function update(Request $request){
        $question_id = $request['question_id'];
        $question = Question::find($question_id)->first();
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        if ($request->has('text')) {
            $question->text = $request->text;
            $question->save();
        }
        if ($request->has('level')) {
            $question->level = $request->level;
            $question->save();
        }
        if ($request->has('mark')) {
            $question->mark = $request->mark;
            $question->save();
        }
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Edit question';
        $log->save();
        return response()->json(['message' => 'Question updated successfully'], 200);
    }

    public function delete(Request $request){
      $question_id = $request->validated()['question_id'];
      $question = Question::find($question_id)->first();
      if ($question) {
            $question->delete();
            $user=Auth::user();
            $employee=$user->employee;
            $log = new LogFile();
            $log->employee_id= $employee->id;
            $log->action = 'Delete Question';
            $log->save();
            return response()->json(['message' => 'Question deleted Successfully'], 200);
        }
        return response()->json(['message' => 'Question not found'], 404);
    }
    public function getQuestionById(Request $request){
        
      $question=Question::find($request['question_id']);
      return response()->json($question, 200);
}}
