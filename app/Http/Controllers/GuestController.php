<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvRequest;
use App\Models\Advertisment;
use App\Models\Cv;
use App\Models\Employee;
use App\Models\Test;
use App\Models\Guest;
use App\Models\GuestQuestionList;
use App\Models\Image;
use App\Models\Question_List;
use App\Models\Teacher;
use App\Models\User;
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
            'answers.*.answer_id' => 'required',
        ]);
    
        $guest = Guest::findOrFail($validatedData['guest_id']);
        $test = Test::findOrFail($validatedData['test_id']);
    
        foreach ($validatedData['answers'] as $answerData) {
            $questionList = Question_List::findOrFail($answerData['question_list_id']);
    
            $guestAnswer = new GuestQuestionList();
            $guestAnswer->answer_id = $answerData['answer_id'];
            $guestAnswer->question_list()->associate($questionList);
            $guest->questions()->save($guestAnswer);
            
        }
    
        return response()->json(['message' => 'Answers stored successfully']);
    }
    
    public function uploadCv(CvRequest $request) {
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

    public function getTeacher() {
    $teachers = Teacher::get()->pluck('employee_id');
    $users = [];

    foreach ($teachers as $teacher) {
        $employee = Employee::where('id', $teacher)->get()->pluck('user_id');
        $user = User::where('id', $employee)->first();

        if ($user) {
            $users[] = $user;
        }
    }

    return response()->json($users);
}

    public function getImage() {
        $images=Image::all();
        return response()->json($images, 200);

        
        }
        public function getAddvertisment() {
    $add= Advertisment::paginate(3);
    return response()->json($add, 200);
    
    }}