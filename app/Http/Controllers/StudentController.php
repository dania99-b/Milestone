<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Attendence;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendenceRequest;


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
        
        return response()->json(['message' => 'Attendance recorded']);
    } else {
        // The QR code is incorrect
        return response()->json(['message' => 'Invalid QR code'], 400);
    }
}
    }

