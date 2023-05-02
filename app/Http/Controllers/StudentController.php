<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Attendence;
use App\Models\StudentRate;
use Illuminate\Http\Request;
use App\Http\Requests\RateRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendenceRequest;

class StudentController extends Controller
{
    public function scan(AttendenceRequest $request)
    {

        $courseId = $request->validated()['course_id'];
        $qrCode = $request->validated()['qr_code'];
        $course = Course::find($courseId)->first();
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;

        if ($course->qr_code == $qrCode) {
            // The QR code is correct, mark the student as present
            // For example, you can create a new attendance record in the database
            $attendance = new Attendence;
            $attendance->student_id = $student;
            $attendance->course_id = $course->id;
            $attendance->save();

            return response()->json(['message' => 'Attendance recorded'], 200);
        } else {
            // The QR code is incorrect
            return response()->json(['message' => 'Invalid QR code'], 400);
        }
    }

   
    public function viewProfileStudent()
    {
        $user = JWTAuth::parseToken()->authenticate();
    $student = Student::where('user_id', $user->id)->first();
    if ($student) {
     
            $user->image = $student->image;
           
            return response()->json($user, 200);
        }
    
    return response()->json(['error' => 'Teacher profile not found.'], 404);
    }
    public function viewNotification(){
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->user_id;
       
        $notifications = DB::table('notifications')
                            ->where('notifiable_type', 'App\Models\User') // Filter by notifiable type
                            ->where('notifiable_id', $student) // Filter by notifiable id
                            //->orderBy('created_at', 'desc') // Order by creation time
                            ->get();
        
        // Convert the result to DatabaseNotification instances
       return  response()->json([$notifications], 200);
        
        // You can now use the $notifications collection to display the notifications
        

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
    public function editProfile(Request $request){
        $student_id = Student::where('user_id',JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;

        $student = Student::find($student_id);
        if (!$student) {
          // Return a 400 status code with an error message if the course cannot be found
          return response()->json(['message' => 'Student not found'], 400);
        }
        if ($request->has('image')) {
          $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
          $student->image = $upload;
          $student->save();
      }
      $user_id=$student->user_id;
        $user = User::find($user_id);
        if ($request->has('first_name')){
            $user->first_name = $request->first_name;
            $user->save();
        }
        if ($request->has('last_name')){
            $user->last_name = $request->last_name;
            $user->save();
        }
        if ($request->has('birthdate')) {
            $user->birthdate = $request->birthdate;
            $user->save();
          }
        if ($request->has('email')){
            $user->email = $request->email;
            $user->save();
        }
        if ($request->has('phone')){
            $user->phone = $request->phone;
            $user->save();
        }
    
        return response()->json(['message' => 'Student info updated successfully'], 200);
    
      }

}