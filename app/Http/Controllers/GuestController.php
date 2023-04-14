<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvRequest;
use App\Models\Advertisment;
use App\Models\Cv;
use App\Models\Test;
use App\Models\Guest;
use App\Models\GuestQuestionList;
use App\Models\Question_List;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function storeAnswers(Request $request)
    {
        $validatedData = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'test_id' => 'required|exists:tests,id',
            'answers' => 'required|array',
            'answers.*.question_list_id' => 'required',
            'answers.*.answer' => 'required',
        ]);
    
        $guest = Guest::findOrFail($validatedData['guest_id']);
        $test = Test::findOrFail($validatedData['test_id']);
    
        foreach ($validatedData['answers'] as $answerData) {
            $questionList = Question_List::findOrFail($answerData['question_list_id']);
    
            $guestAnswer = new GuestQuestionList();
            $guestAnswer->answer = $answerData['answer'];
            $guestAnswer->question_list()->associate($questionList);
            $guest->questions()->save($guestAnswer);
            
        }
    
        return response()->json(['message' => 'Answers stored successfully']);
    }
    
    public function uploadCv(CvRequest $request){
        $file=$request->file('file')->move('pdf/', $request->file('file')->getClientOriginalName());
        $guest = Guest::find($request->validated()['guest_id']);
     //   $advertisment = Advertisment::find($request->validated()['advertisment_id']);
        if($guest){
        $cv=Cv::firstOrCreate([
         'guest_id'=>$request->validated()['guest_id'],
         'file'=>$file
        ]);
    }}

}
