<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Attendence;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendenceRequest;
use App\Http\Requests\RateRequest;
use App\Models\StudentRate;
use App\Models\User;

class StudentController extends Controller
{
    public function scan(AttendenceRequest $request)
{   

    $courseId=$request->validated()['course_id'];
    $qrCode = $request->validated()['qr_code'];
    $course=Course::find($courseId)->first();
    $student = Student::where('user_id',JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
  
    if ($course->qr_code == $qrCode) {
        // The QR code is correct, mark the student as present
        // For example, you can create a new attendance record in the database
        $attendance = new Attendence;
        $attendance->student_id = $student;
        $attendance->course_id = $course->id;
        $attendance->save();
        
        return response()->json(['message' => 'Attendance recorded'],200);
    } else {
        // The QR code is incorrect
        return response()->json(['message' => 'Invalid QR code'], 400);
    }
}

public function rate(RateRequest $request)
{ 
    $student = Student::where('user_id',JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;

    $rate = StudentRate::firstOrCreate([
        'student_id' => $student,
        'teacher_id' => $request->validated()['teacher_id'],
        'rate' =>$request->validated()['rate'],
    ]); 
    }
    public function viewProfileStudent(){
        $student = Student::where('user_id',JWTAuth::parseToken()->authenticate()->id)->get()->first()->user_id;
        $user=User::find($student);
        return response()->json($user, 200);


    }

}
