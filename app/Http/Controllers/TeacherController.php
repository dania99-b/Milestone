<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Day;
use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use App\Models\Answer;
use App\Models\Course;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Homework;
use App\Models\Question;
use App\Models\StudentRate;
use App\Models\CourseResult;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use App\Models\EducationFile;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LeaveAndResignation;
use App\Events\NotificationRecieved;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HomeworkRequest;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\EducationFileRequest;
use App\Http\Requests\LeaveOrResignationRequest;
use App\Models\Attendence;
use App\Models\Notification;
use App\Notifications\WebSocketSuccessNotification;

class TeacherController extends Controller
{
//   public function AddQuestionExistTest(Request $request)
//   {
//     $test_id = $request['test_id'];
//     $test = Test::find($test_id);
//     if (!$test) {
//       return response()->json(['message' => 'Question not found'], 400);
//     }

//     $questions = $request->input('question_id');
//     foreach ($questions as $question) {
//       $questionModel = Question::where('id', $question)->firstOrFail();
//       $test->questions()->attach($questionModel);
     
//     }
//     $user=Auth::user();
//     $employee=$user->employee;
//     $log = new LogFile();
// $log->employee_id= $employee->id;
// $log->action = 'Add Question To Exist Test';
// $log->save();
// return response()->json(['message' => 'Question Added Successfully'], 200);
//   }

  public function getrand()
  {
    $questions = Question::orderBy(DB::raw('RAND()'))->take(10)->get();
    return $questions;
  }

  public function viewProfileTeacher()
  {
    $user = JWTAuth::parseToken()->authenticate();
    $employee = Employee::where('user_id', $user->id)->first();
    if ($employee) {
      $teacher = Teacher::where('employee_id', $employee->id)->first();
      if ($teacher) {
        $user->image = $employee->image;

        return response()->json($user, 200);
      }
    }
    return response()->json(['error' => 'Teacher profile not found.'], 404);
  }

  public function editProfile(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    $teacher=$employee->teacher;
   
    if (!$teacher) {
      return response()->json(['message' => 'Teacher not found'], 404);
  }

  if ($request->hasFile('image')) {
      $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $employee->image = $image;
  }

  $user = $employee->user;
  $user->fill($request->only(['first_name', 'last_name', 'birth', 'email', 'phone','username','gender']));

  if ($user->isDirty()) {
      $user->save();
  }

  if ($employee->isDirty()) {
      $employee->save();
  }
  
  if ($request->has('period_id')) {
    $periodIds = $request->input('period_id');
    $teacher->periods()->sync($periodIds); // Sync the period_ids in the pivot table
    $teacher->save();
}

  $user = JWTAuth::parseToken()->authenticate();
  $log = new LogFile();
  $log->user_id= $user->id;
$log->action = 'Edit Own Profile';
$log->save();
  return response()->json(['message' => 'Teacher info updated successfully'], 200);
}

public function list(){
  
  $teachers = Teacher::with('employee.user')->get();
  
  return response()->json($teachers, 200);
}

public function uploadHomework(HomeworkRequest $request){
  if ($request->hasFile('file')) {
    $filename = $request->file('file')->move('files/', $request->file('file')->getClientOriginalName());
  
} else {
    $filename = null;
}
  $homework=Homework::firstOrCreate([
    'course_id'=>$request->validated()['course_id'],
         'text'=>$request->validated()['text'],
         'file'=>$filename
  ]);
  $user = JWTAuth::parseToken()->authenticate();
  $log = new LogFile();
      $log->user_id = $user->id;
      $log->action = 'Upload Homework';
      $log->save();
return response()->json(['message'=>'Homework Uploaded Successfully'],200);
}

public function uploadLeave(LeaveOrResignationRequest $request){
 
  $user = JWTAuth::parseToken()->authenticate();
  $employee = $user->employee;
  LeaveAndResignation::FirstOrCreate([
            'employee_id'=>$employee->id,
              'reason'=>$request->validated()['reason'],
              'from'=>Carbon::now(),
              'type'=>"Resignation"

  ]);

  $log = new LogFile();
      $log->user_id = $user->id;
      $log->action = 'Upload Leave Request';
      $log->save();
return response()->json(['message'=>'Leaver Request Uploaded Successfully'],200);

}

  public function uploadResignation(LeaveOrResignationRequest $request){
    $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    $data = [
        'employee_id' => $employee->id,
        'reason' => $request->validated()['reason'],
        'from' => $request->validated()['from'],
        'to' => $request->validated()['to'],
        'type' => "Leave",
        'comment' => $request->validated()['comment']
    ];

    if ($request->hasFile('file')) {
        $file = $request->file('file')->move('files/', $request->file('file')->getClientOriginalName());
        $data['file'] = $file;
    }

    $Resignation=LeaveAndResignation::Create($data);
$Resignation->save();
$log = new LogFile();
    $log->user_id = $user->id;
    $log->action = 'Upload Residnation';
    $log->save();
return response()->json(['message'=>'Resignation Uploaded Successfully'],200);
            }


  public function deleteLeave($id){
       
    $leave = LeaveAndResignation::find($id);
    if (!$leave) {
        return response()->json(['message' => 'Class not found'], 400);
    }
    $leave->delete();
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
    $log->user_id= $user->id;
    $log->action = 'Delete Leave Or Resignation';
    $log->save();
   
    $log = new LogFile();
        $log->user_id = $user->id;
        $log->action = 'Delete Leave Or Resignation';
        $log->save();

    return response()->json(['message' => 'Leave Or Resignation deleted successfully'], 200);
}
public function getAllLeave(){
  $leaves=LeaveAndResignation::with('employee.user')->get();
  return response()->json($leaves,200);
}

public function getRequest(){
  $user = JWTAuth::parseToken()->authenticate();
  $employee = $user->employee;
  $requests=LeaveAndResignation::where('employee_id',$employee->id)->get();
  return response()->json($requests,200);
}
public function getRequestById($id){
  $requests=LeaveAndResignation::find($id);
  return response()->json($requests,200);
}

public function getCourseStudent($course_id)
{

    $course = Course::find($course_id);

    if ($course) {
        $students = $course->course_result()->with('student.user')->get()->pluck('student');
        return response()->json($students, 200);
    }

    return response()->json(['message' => 'Course not found.'], 404);
}

public function getTeacheCourse(){
  $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    $teacher=$employee->teacher;
    $course=Course::where('teacher_id',$teacher->id)->get();
    return response()->json($course,200);

}
public function getActiveCourse()
{
    $now = Carbon::now();
    $courses = Course::with('period:id,start_hour,end_hour')->with("courseName:id,name")->with('class:id,name')->where('end_day', '>', $now)->get();

    $courses = $courses->map(function ($course) {
        $course->name = $course->courseName->name;
        $course->class_name = $course->class->name;
        $course->days =  collect(json_decode($course->days))->map(function ($dayId) {
          return Day::find($dayId);
      });
      $course->start_hour=$course->period->start_hour;
      $course->end_hour=$course->period->end_hour;
        unset($course->courseName);
        unset($course->period);
        unset($course->class);
        return $course;
    });
   
   

    return $courses;
}
public function getActiveCourseByTeacherId()
{
  $user = JWTAuth::parseToken()->authenticate();
  $employee = $user->employee;
  $teacher=$employee->teacher;

$now = Carbon::now();
$courses = Course::with('period:id,start_hour,end_hour')->with("courseName:id,name")->where('teacher_id',$teacher->id)->where('end_day', '>', $now)->get();
$courses = $courses->map(function ($course) {
  $course->name = $course->courseName->name;
  $course->days =  collect(json_decode($course->days))->map(function ($dayId) {
    return Day::find($dayId);
});
$course->start_hour=$course->period->start_hour;
$course->end_hour=$course->period->end_hour;
  unset($course->courseName);
  unset($course->period);
  return $course;
});
return response()->json($courses,200);

}



public function sendZoomNotification(Request $request)
{
    $course_id = $request['course_id'];
    $zoom_url = $request['zoom_url'];
    $decoded_zoom_url = html_entity_decode($zoom_url);
    $student_in_this_course = CourseResult::where('course_id', $course_id)->get();
    $array_of_userid = [];

    for ($i = 0; $i < count($student_in_this_course); $i++) {
        $student_id = $student_in_this_course[$i]->student_id;
        $user = Student::find($student_id)->user_id;
        $array_of_userid[$i] = $user;
    }


    // Send notification to each user with the "student" role
    foreach ($array_of_userid as $item) {
    
        $curr_user = User::find($item);
        $notificationHelper = new NotificationController();
    $msg = array(
        "title" => "new Meeting",
        "body" => $decoded_zoom_url,
    );
    $notifyData = [
      "title" =>  "new Meeting",
      "body"  =>  $decoded_zoom_url,
    ];
   
    foreach ($curr_user->fcmtokens as $fcmtoken) {
     
        $notificationHelper->send($fcmtoken->fcm_token, $msg, $notifyData);
    }
    $notification = new Notification();
    $notification->user_id =$curr_user->id;
    $notification->title = implode(', ', $msg);
    $notification->body = implode(', ', $notifyData);
    $notification->save();
    }

    $user1 = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
        $log->user_id = $user1->id;
        $log->action = 'Send Zoom Notification';
        $log->save();
  return response()->json(['message'=>'Zoom Notification Sent Successfully'],200);
 
}

public function getAttendence($course_id){
  $attendence=Attendence::where('course_id',$course_id)->with('student.user')->get();
  return response()->json($attendence,200);
}
public function getAttendenceCourseDayByDay(Request $request)
{
    $courseId = $request->input('course_id');
    $course = Course::findOrFail($courseId);
    $attendanceData = [];

    $startDate = new \DateTime($course->start_day);
    $endDate = new \DateTime($course->end_day);

    $currentDate = clone $startDate;
    $dayCount = 0;
    while ($currentDate <= $endDate) {
        if ($dayCount % 2 == 0) { // On days
            $attendance = Attendence::where('course_id', $course->id)
                ->whereDate('created_at', $currentDate->format('Y-m-d'))
                ->with(['student.user:id,first_name,last_name']) // Load specific user attributes
                ->get();

            $formattedAttendance = $attendance->map(function ($item) {
                return [
                    'id' => $item->id,
                    'first_name' => $item->student->user->first_name,
                    'last_name' => $item->student->user->last_name,
                ];
            });

            $attendanceData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'attendance' => $formattedAttendance,
            ];
        }
        $dayCount++;

        $currentDate->add(new \DateInterval('P1D'));
    }

    return response()->json($attendanceData);
}






}