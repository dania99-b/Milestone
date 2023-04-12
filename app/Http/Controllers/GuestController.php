<?php

namespace App\Http\Controllers;

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
    

}
