<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\LogFile;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionType;

class PlacementController extends Controller
{
    public function generate(TestRequest $request){
        $questionTypes = QuestionType::with('questions')->get();
        $selectedQuestions = [];
        //For testing
        $firstTypeQuestions = 4;
        $secondTypeQuestions = 6;
        foreach ($questionTypes as $questionType) {
            $questions = $questionType->questions->shuffle();
            if ($questionType->id === 1) {
                $selectedQuestions = array_merge($selectedQuestions, $questions->take($firstTypeQuestions)->toArray());
            } elseif ($questionType->id === 2) {
                $selectedQuestions = array_merge($selectedQuestions, $questions->take($secondTypeQuestions)->toArray());
            }
        }
          // $questions = $questions = Question::with('answers')->orderBy(DB::raw('RAND()'))->take(2)->get();
          $newTest = Test::firstOrCreate([
            'start_date' => $request->validated()['start_date'],
            'end_date' => $request->validated()['end_date'],],
            [
              'questions' => json_encode($selectedQuestions->pluck('id')),
            ]);
          $user=Auth::user();
          $employee=$user->employee;
          $log = new LogFile();
          $log->employee_id= $employee->id;
          $log->action = 'Create Test';
          $log->save();
          return response()->json(['message' => 'Test Created Successfully', $newTest], 200);
      }
    
}
