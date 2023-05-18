<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\Test;
use App\Models\User;
use App\Models\Guest;
use App\Models\Image;
use App\Models\Answer;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Question;
use App\Models\Advertisment;
use Illuminate\Http\Request;
use App\Models\Question_List;
use App\Models\GuestPlacement;
use App\Http\Requests\CvRequest;
use App\Models\GuestQuestionList;

class GuestController extends Controller
{
    public function storeAnswers(Request $request){
        $total_mark = 0;
        $validatedData = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'test_id' => 'required|exists:tests,id',
            'answers' => 'required|array',
           
        ]);
       
        foreach($validatedData['answers'] as $answer){
        $answer=Answer::find($answer);
        if($answer->is_true==1)
    
         $total_mark+=Question::find($answer->question_id)->mark;
        }
       
      
        $store=GuestPlacement::firstOrCreate([
            'guest_id'=>$validatedData['guest_id'],
            'test_id'=>$validatedData['test_id'],
            'mark'=>  $total_mark
            
           ]);
        $store->save();
     
        return response()->json(['message' => 'Answer Submited Successfully ', 'data'=> $total_mark],200);
    }

    public function uploadCv(CvRequest $request){
        $file = $request->file('file')->move('pdf/', $request->file('file')->getClientOriginalName());
        $guest = Guest::find($request->validated()['guest_id']);
        $cvData = [
            'guest_id' => $request->validated()['guest_id'],
            'file' => $file
        ];
        if ($request->has('advertisment_id')) {
            $cvData['advertisment_id'] = $request->validated()['advertisment_id'];
        }
        if ($guest) {
            $cv = Cv::firstOrCreate($cvData);
        }
        return response()->json(['message' => 'CV uploaded successfully'], 200);
    }

    public function teachersList(){
        $teachers = Teacher::with('employee.user')->get();
        $users = $teachers->pluck('employee.user')->map(function ($user) {
            $user->image = $user->employee->image;
            unset($user->employee);
            return $user;
        })->filter();
        return response()->json($users, 200);
    }

    public function imagesList(){
        $images = Image::all();
        return response()->json($images, 200);
    }

    public function advertisementsList(){
        $ads = Advertisment::all();
        return response()->json($ads, 200);
    }
}
