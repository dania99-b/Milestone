<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\Day;
use App\Models\User;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Country;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\Homework;
use App\Models\Question;
use App\Models\Attendence;
use App\Models\StudentRate;
use App\Models\Advertisment;
use App\Models\CourseResult;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\CvRequest;
use App\Models\StudentPlacement;
use App\Http\Requests\RateRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendenceRequest;

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
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Submit Answers';
            $log->save();
     
        return response()->json(['message' => 'Answer Submited Successfully ', 'data'=> $total_mark],200);
    }

    public function scan(AttendenceRequest $request)
    {
        $qrCode = $request->validated()['qr_code'];
       
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;

        $courseId =CourseResult::where('student_id',$student)->latest()->value('course_id');
       
        if($courseId){
        $course = Course::find($courseId);
        
        if ($course->qr_code == $qrCode) {
           
            $attendance = new Attendence;
            $attendance->student_id = $student;
            $attendance->course_id = $course->id;
    
            $attendance->save();
            $user = JWTAuth::parseToken()->authenticate();
            $log = new LogFile();
                $log->user_id = $user->id;
                $log->action = 'Scan Barcode';
                $log->save();
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
        $user = JWTAuth::parseToken()->authenticate();
        $student = $user->student;
 
        $course_result=CourseResult::where('student_id',$student->id)->latest()->value('course_id');
        
        $course_teacher=Course::where('id',$course_result)->get()->value('teacher_id');
      
        $rate = StudentRate::firstOrCreate([
            'student_id' => $student->id,
            'teacher_id' => $course_teacher,
            'rate' => $request->validated()['rate'],
            'note' => $request->validated()['note'],
            'course_id' => $course_result,
        ]);
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Rate Teacher';
            $log->save();
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
        $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone','username']));

        if ($user->isDirty()) {
            $user->save();
        }

        if ($student->isDirty()) {
            $student->save();
        }
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Edit Student Profile';
            $log->save();
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
        $course_info=Course::with('period:id,start_hour,end_hour,is_available')->find($curr_course_id);
   if ($course_info) {
        $course_info->start_hour = $course_info->period->start_hour;
        $course_info->end_hour = $course_info->period->end_hour;
        $course_info->is_available = $course_info->period->is_available;
        unset($course_info->period);
    }

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
    $curr_course_id =CourseResult::where('student_id', $student->id)
    ->with(['course.courseName:id,name','mark'])
    ->select('id', 'total', 'status', 'course_id', 'student_id', 'created_at', 'updated_at')
    ->get();

$curr_course_id->transform(function ($item) {
    $item->course_name = $item->course->courseName->name;
    unset($item->course);
    return $item;
});
    return response()->json($curr_course_id,200);


}

public function getAllMarksForSpecificCourse(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();
    $student = $user->student;
   
    $course_id = $request['course_id'];
    
    $curr_course_id = CourseResult::where('student_id', $student->id)
        ->where('course_id', $course_id)
        ->with(['course.courseName:id,name', 'mark'])
        ->select('id', 'total', 'status', 'course_id', 'student_id', 'created_at', 'updated_at')
        ->get();

    $curr_course_id->transform(function ($item) {
        $item->course_name = $item->course->courseName->name;
        unset($item->course);
        return $item;
    });
    
    // Debug output to check if rates exist
    $ratesExist = $this->checkIfrates($student, $course_id);

    if ($ratesExist) {
        return response()->json($curr_course_id, 200);
    } else {
        return response()->json(['message' => 'Cannot watch the marks before making rate'], 400);
    }
}

public function checkIfrates($student, $course_id)
{
    return StudentRate::where('student_id', $student->id)
        ->where('course_id', $course_id)
        ->exists();
}










public function deleteNotification(Request $request){
    $id=$request['id'];
    $user = JWTAuth::parseToken()->authenticate();
    $notification=Notification::where('notifiable_id',$user->id)->where('id',$id);
    $notification->delete();
return response()->json(["meassage"=>"Notification Deleted Successfully"],200);

}

public function getNotification()
{
    $user = JWTAuth::parseToken()->authenticate();
    $notification=Notification::where('user_id',$user->id)->get();
    return response()->json($notification,200);

}

public function uploadCv(Request $request){
    $file = $request->file('file')->move('pdf/', $request->file('file')->getClientOriginalName());
    $user = JWTAuth::parseToken()->authenticate();
    $student = $user->student;
    if( $student){
    $cvData = [
        'student_id' =>  $student->id,
        'file' => $file
    ];
    if ($request->has('advertisment_id')) {
        $cvData['advertisment_id'] = $request['advertisment_id'];
    }
    if ($student) {
        $cv = Cv::firstOrCreate($cvData);
    }
    return response()->json(['message' => 'CV uploaded successfully'], 200);
}
else   return response()->json(['message' => 'Guest Not Found'], 400);
}
public function advertisementsList(){
    $advertisment = Advertisment::with(['advertismentType'])
    ->whereHas('advertismentType', function ($query) {
        $query->where('shown_for',2)
        ->orWhere('shown_for',3)
        ->orWhere('shown_for',4)
        ->orWhere('shown_for',1);
    })
    ->get();
    return response()->json($advertisment, 200);
}


}