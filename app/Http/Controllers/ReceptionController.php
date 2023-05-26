<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\User;
use App\Models\Classs;
use App\Models\Course;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Course_Day;
use Illuminate\Http\Request;
use App\Models\Class_Schedule;
use App\Models\Teacher_Schedule;
use App\Http\Requests\ClassRequest;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdvertismentRequest;
use App\Http\Requests\ClassScheduleRequest;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Requests\TeacherScheduleRequest;
use App\Models\Guest;
use App\Models\GuestPlacement;
use App\Models\Reception;

class ReceptionController extends Controller
{
  

  public function EditStudentInfo(Request $request)
  {
    $student_id = $request['student_id'];
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
    $user_id = $student->user_id;
    $user = User::find($user_id);
    if ($request->has('first_name')) {
      $user->first_name = $request->first_name;
      $user->save();
    }
    if ($request->has('last_name')) {
      $user->last_name = $request->last_name;
      $user->save();
    }
    if ($request->has('email')) {
      $user->email = $request->email;
      $user->save();
    }
    if ($request->has('phone')) {
      $user->phone = $request->phone;
      $user->save();
    }

    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Edit Student information';
$log->save();
    return response()->json(['message' => 'Student info updated successfully'], 200);
  }
  

  public function ScheduleClass(ClassScheduleRequest $request)
  {
    $ClassId = $request->validated()['class_id'];
    $class = Classs::find($ClassId);
    if ($class) {
      $newSchedule = Class_Schedule::Create([
        'class_id' => $ClassId,
        'day' => $request->validated()['day'],
        'start_time' => date('H:i:s', strtotime($request->validated()['start_time'])),
        'end_time' => $request->validated()['end_time'],

      ]);
      $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Create Schedule Class';
$log->save();
      return response()->json(['message' => 'Schedule Created Successfully'], 200);
    }
    return response()->json(['message' => 'Schedule Created Error'], 400);
  }

  
  public function EditScheduleClass(Request $request)
  {
    $id = $request['schedule_id'];
    $schedule = Class_Schedule::find($id);
   

    if (!$schedule) {
      return response()->json(['message' => 'Schedule not found'], 400);
    }


    $schedule->fill($request->only(['day', 'start_time', 'end_time']));

    if ($schedule->isDirty()) {
      $schedule->save();
    }
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Edit Schedule Class';
$log->save();

    return response()->json(['message' => 'Schedule info updated successfully'], 200);
  }

  public function EditScheduleTeacher(Request $request)
  {
    $id = $request['schedule_id'];
    $schedule = Teacher_Schedule::find($id);

    if (!$schedule) {
      return response()->json(['message' => 'Schedule not found'], 400);
    }


    $schedule->fill($request->only(['day', 'start_time', 'end_time']));

    if ($schedule->isDirty()) {
      $schedule->save();
    }

    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Edit Schedule Teacher';
$log->save();
    return response()->json(['message' => 'Schedule info updated successfully'], 200);
  }

  public function deleteScheduleTeacher(Request $request)
  {
    $id = $request['schedule_id'];
    $schedule = Teacher_Schedule::find($id);


    if (!$schedule) {
      return response()->json(['message' => 'Schedule not found'], 400);
    }

    $schedule->delete();
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Delete Schedule Teacher';
$log->save();

    return response()->json(['message' => 'Schedule deleted successfully'], 200);
  }

  public function deleteScheduleClass(Request $request)
  {

    $schedule = Class_Schedule::find( $request['schedule_id'])->get();


    if (!$schedule) {
      return response()->json(['message' => 'Schedule not found'], 400);
    }

    $schedule->delete();
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogFile();
$log->employee_id= $employee->id;
$log->action = 'Delete Schedule Class';
$log->save();

    return response()->json(['message' => 'Schedule deleted successfully'], 200);
  }
  public function list(){
  
    $receptions = Reception::with('employee.user')->get();
    
    return response()->json($receptions, 200);
  }

  
}
