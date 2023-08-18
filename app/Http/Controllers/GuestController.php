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
use App\Models\StudentRate;
use App\Models\Advertisment;
use Illuminate\Http\Request;
use App\Models\Question_List;
use App\Models\GuestPlacement;
use Illuminate\Support\Carbon;
use App\Http\Requests\CvRequest;
use App\Models\GuestQuestionList;

class GuestController extends Controller
{
    public function storeAnswers(Request $request)
{
    $total_mark = 0;
    $validatedData = $request->validate([
        'test_id' => 'required|exists:tests,id',
        'answers' => 'required|array',
        'device_id'=>'required'
    ]);
   
    $guest = Guest::where('device_id', $request->device_id)->first();
    if (!$guest) {
        // Handle the case when guest is not found for the device_id
        return response()->json(['message' => 'Guest not found'], 404);
    }

    foreach ($validatedData['answers'] as $answerId) {
        $answer = Answer::find($answerId);
        if ($answer && $answer->is_true == 1) {
            $total_mark += Question::find($answer->question_id)->mark;
        }
    }

    $store = GuestPlacement::firstOrCreate([
        'guest_id' => $guest->id,
        'test_id' => $validatedData['test_id'],
        'mark' => $total_mark
    ]);

    $store->save();

    return response()->json(['message' => 'Answer Submitted Successfully', 'data' => $total_mark], 200);
}


    public function uploadCv(CvRequest $request){
        $file = $request->file('file')->move('pdf/', $request->file('file')->getClientOriginalName());
        $device_id= $request->validated()['device_id'];
        $guest=Guest::where('device_id',$device_id)->get()->value('id');
        if( $guest){
        $cvData = [
            'guest_id' =>  $guest,
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
    else   return response()->json(['message' => 'Guest Not Found'], 400);
    }

    public function teachersList()
    {
        $teachers = Teacher::with('employee.user')->get();
        $users = $teachers->pluck('employee.user')->map(function ($user) {
            $user->image = $user->employee->image;
            $user->experience_years = $user->employee->teacher->experience_years;
            unset($user->employee);
            return $user;
        })->filter();
    
        $monthlyRates = [];
        foreach ($teachers as $teacher) {
            $totalRates = 0;
            $teacherCount = 0;
    
            $teacher_rates = StudentRate::where('teacher_id', $teacher->id)
                ->whereBetween('created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])
                ->get();
    
            $totalRates = $teacher_rates->sum('rate');
            $teacherCount = $teacher_rates->count();
            $averageRate = ($teacherCount > 0) ? $totalRates / $teacherCount : 0;
            $convertedRate = $averageRate * 5 / 100; // Convert to a scale of 5
    
            $monthlyRates[$teacher->id] = $convertedRate;
        }
    
        $response = $teachers->map(function ($teacher) use ($monthlyRates) {
            $user = $teacher->employee->user;
            $user->image = $teacher->employee->image;
            $user->experience_years = $teacher->experince_years;
            $user->rate = $monthlyRates[$teacher->id] ?? 0;
            return $user;
        });
    
        return response()->json($response, 200);
    }
    

    public function imagesList(){
        $images = Image::all();
        return response()->json($images, 200);
    }

    public function advertisementsList(){
        $advertisment = Advertisment::with(['advertismentType'])
        ->whereHas('advertismentType', function ($query) {
            $query->where('shown_for',2)
            ->orWhere('shown_for',3);
           
        })
        ->get();
        return response()->json($advertisment, 200);
    }
}
