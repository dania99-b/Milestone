<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\User;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Country;
use App\Models\Student;
use App\Models\Question;
use App\Models\Attendence;
use App\Models\StudentRate;
use App\Models\CourseResult;
use Illuminate\Http\Request;
use App\Models\StudentPlacement;
use App\Http\Requests\RateRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendenceRequest;
use App\Models\Homework;
use App\Models\Notification;

class StudentController extends Controller
{




    public function storeAnswers(Request $request){
        $total_mark = 0;
        $validatedData = $request->validate([
           
            'test_id' => 'required|exists:tests,id',
            'answers' => 'required|array',
           
        ]);
       
        foreach($validatedData['answers'] as $answer){
        $answer=Answer::find($answer);
        if($answer->is_true==1)
    
         $total_mark+=Question::find($answer->question_id)->mark;
        }
       
      $user= JWTAuth::parseToken()->authenticate();
    

        $store=StudentPlacement::firstOrCreate([
            'student_id'=>  $user->student->id,
            'test_id'=>$validatedData['test_id'],
            'mark'=>  $total_mark
            
           ]);
        $store->save();
     
        return response()->json(['message' => 'Answer Submited Successfully ', 'data'=> $total_mark],200);
    }

    public function scan(AttendenceRequest $request)
    {

        
        $qrCode = $request->validated()['qr_code'];
       
      
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
    
        $courseId =CourseResult::where('student_id',$student)->latest()->value('course_id');
       
        if($courseId){
        $course = Course::find($courseId)->first();
        
    
        if ($course->qr_code == $qrCode) {
           
            $attendance = new Attendence;
            $attendance->student_id = $student;
            $attendance->course_id = $course->id;
            $attendance->save();

            return response()->json(['message' => 'Attendance recorded'], 200);
        } else {
            // The QR code is incorrect
            return response()->json(['message' => 'Invalid QR code'], 400);
        }}
        else   return response()->json(['message' => 'Student Not In This Course'], 400);
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
    public function viewNotification()
    {
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
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;

        $rate = StudentRate::firstOrCreate([
            'student_id' => $student,
            'teacher_id' => $request->validated()['teacher_id'],
            'rate' => $request->validated()['rate'],
            'note' => $request->validated()['note'],
        ]);
        return response()->json(['message' => 'Rate is successfully sent'], 200);
    }
    public function editProfile(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        $student = $user->student;

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());

            $student->image = $image;
        }

        $user = $student->user;
        $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone']));

        if ($user->isDirty()) {
            $user->save();
        }

        if ($student->isDirty()) {
            $student->save();
        }

        return response()->json(['message' => 'Student info updated successfully'], 200);
    }

    public function countries(){
        $countries = Country::all();
        return response()->json($countries, 200);
    }

    public function getAttendenceDays(){
        $user = JWTAuth::parseToken()->authenticate();
        $student = $user->student;
        $curr_course_id=CourseResult::where('student_id',$student->id)->latest()->value('course_id');
        $course_info=Course::find($curr_course_id);
   
        if ($course_info) 
        $course_info->days = collect(json_decode($course_info->days))->map(function ($dayId) {
            return Day::find($dayId);
        });
            
    return response()->json($course_info,200);}


public function getHomeworkCurrCourse(){
    $user = JWTAuth::parseToken()->authenticate();
    $student = $user->student;
    $curr_course_id=CourseResult::where('student_id',$student->id)->latest()->value('course_id');
    $course_info=Homework::where('course_id',$curr_course_id)->get();


return response()->json($course_info,200);

}

public function getAllMarks(){
    $user = JWTAuth::parseToken()->authenticate();
    $student = $user->student;
    $curr_course_id=$curr_course_id =CourseResult::where('student_id', $student->id)
    ->with(['course.courseName:id,name','mark'])
    ->select('id', 'total', 'status', 'course_id', 'student_id', 'created_at', 'updated_at')
    ->get();

$curr_course_id->transform(function ($item) {
    $item->course_name = $item->course->courseName;
    unset($item->course);
    return $item;
});
    return response()->json($curr_course_id,200);


}
public function deleteNotification(Request $request){
    $id=$request['id'];
    $user = JWTAuth::parseToken()->authenticate();
    $notification=Notification::where('notifiable_id',$user->id)->where('id',$id);
    $notification->delete();
return response()->json(["meassage"=>"Notification Deleted Successfully"],200);

}}