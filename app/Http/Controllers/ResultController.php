<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\User;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\CourseResult;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\NotificationRecieved;
use App\Http\Requests\ResultRequest;
use App\Notifications\WebSocketSuccessNotification;

class ResultController extends Controller
{
    public function uploadStudentResult(ResultRequest $request)
    {


        $validatedData = $request->validated();
        $studentId = $validatedData['student_id'];
        $student=Student::find($studentId)->user_id;
        $user=User::find( $student);
        print($user);
       
            // Send notification to each user with the "student" role
         
                $user->notify(new WebSocketSuccessNotification('New Marks Uploaded'));
                event(new NotificationRecieved($user));
            
        
        $courseResult=CourseResult::where('student_id', $studentId)
        ->latest('id')
        ->first();
        
        if ($courseResult) {
            Mark::firstOrCreate([
                'med' => $validatedData['med'],
                'presentation' => $validatedData['presentation'],
                'oral' => $validatedData['oral'],
                'final' => $validatedData['final'],
                'homework' => $validatedData['homework'],
                'course_result_id' => $courseResult->id
            ]);
            
         
            $total = $validatedData['oral'] + $validatedData['presentation'] + $validatedData['homework'] +
                $validatedData['med'] + $validatedData['final'];
            
           
            $status = ($total < 60) ? 'Failed' : 'Passed';
            
           
            $courseResult->update([
                'total' => $total,
                'status' => $status
            ]);
            $user = JWTAuth::parseToken()->authenticate();
            $log = new LogFile();
                $log->user_id = $user->id;
                $log->action = 'Upload Student Result';
                $log->save();
            
            
            return response()->json([
                'message' => 'Student result uploaded successfully',
                'data' => $courseResult
            ]);
        } else {
            return response()->json([
                'message' => 'No course result found for the specified student ID'
            ], 404);
        }
    
}
public function getStudentResultById($student_id)
{
$student=Student::find($student_id);
$course_result=CourseResult::where('student_id',$student->id)->get()->pluck('id')->first();
$marks=Mark::where('course_result_id',$course_result)->get();
return response()->json($marks,200);

}}
