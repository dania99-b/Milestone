<?php

namespace App\Http\Controllers;
use App\Models\Test;
use App\Models\LogFile;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlacementController extends Controller
{
    public function generate(TestRequest $request){
     
        //For testing
       
    
        //we can filter here with type_id
        $questions = Question::with('answers')->orderBy(DB::raw('RAND()'))->take(3)->get();

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
          return response()->json(['message' => 'Test Created Successfully', $newTest], 200);
      }
    
}
