<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\User;
use App\Models\Classs;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course_Day;
use App\Models\LogActivity;
use App\Models\Advertisment;
use Illuminate\Http\Request;
use App\Models\Class_Schedule;
use App\Models\AdvertismentType;
use App\Models\Teacher_Schedule;
use App\Http\Requests\ClassRequest;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdvertismentRequest;
use App\Http\Requests\ClassScheduleRequest;
use App\Http\Requests\TeacherScheduleRequest;
use App\Models\Employee;

class ReceptionController extends Controller
{
  public function OpenCourse(CourseRequest $request)
  {
    $ClassName = $request->validated()['class_name'];
    $ClassId = Classs::where('name', $ClassName)->first()->id;
    $newCourse = Course::firstOrCreate([
      'class_id' => $ClassId,
      'course_ename' => $request->validated()['course_ename'],
      'start_hour' => $request->validated()['start_hour'],
      'end_hour' => $request->validated()['end_hour'],
      'start_day' => $request->validated()['start_day'],
      'end_day' => $request->validated()['end_day'],
      'status' => $request->validated()['status'],
      'qr_code' => $request->validated()['qr_code'],
    ]);
    $days = $request->input('days');
    foreach ($days as $day) {
      $dayModel = Day::where('name', $day)->firstOrFail();
      $newCourse->days()->attach($dayModel);
    }
    return response()->json(['message' => 'Course created successfully'], 200);
  }
  public function OpenClass(ClassRequest $request)
  {
    $newClass = Classs::firstOrCreate([
      'name' => $request->validated()['name'],
      'max_num' => $request->validated()['max_num'],
      'status' => $request->validated()['status'],
    ]);
    $user=Auth::user();
    $employee=$user->employee;
    $log = new LogActivity();
$log->employee_id= $employee->id;
$log->action = 'Opened a New Class';
$log->save();
    return response()->json(['message' => 'Class added successfully'], 200);
  }
  public function EditClass(Request $request)
  {

    $class_id = $request['class_id'];
    $class = Classs::find($class_id);
    if (!$class) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Course not found'], 400);
    }
    if ($request->has('name')) {
      $class->name = $request->name;
      $class->save();
    }

    if ($request->has('max_num')) {
      $class->max_num = $request->max_num;
      $class->save();
    }
    if ($request->has('status')) {
      $class->status = $request->status;
      $class->save();
    }
    return response()->json(['message' => 'Class updated successfully'], 200);
  }
  public function EditCourse(Request $request)
  {
    $course_id = $request['course_id'];
    $course = Course::find($course_id);
    if (!$course) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Course not found'], 400);
    }
    if ($request->has('name')) {
      $course->course_ename = $request->name;
      $course->save();
    }
    if ($request->has('start_hour')) {
      $course->start_hour = $request->start_hour;
      $course->save();
    }
    if ($request->has('end_hour')) {
      $course->end_hour = $request->end_hour;
      $course->save();
    }
    if ($request->has('start_day')) {
      $course->start_day = $request->start_day;
      $course->save();
    }
    if ($request->has('end_day')) {
      $course->end_day = $request->end_day;
      $course->save();
    }
    if ($request->has('status')) {
      $course->status = $request->status;
      $course->save();
    }
    if ($request->has('qr_code')) {
      $course->qr_code = $request->qr_code;
      $course->save();
    }
    if ($request->input('days')) {
      $course->days()->detach();
      foreach ($request->input('days') as $day) {
        $dayModel = Day::where('name', $day)->firstOrFail();
        $course->days()->attach($dayModel);
        $course->save();
      }
    }
    return response()->json(['message' => 'Course updated successfully'], 200);
  }
  public function DeleteCourse(Request $request)
  {
    $id = $request['course_id'];
    $course = Course::find($id);
    if (!$course) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Course not found'], 400);
    }
    $course->delete();
    return response()->json(['message' => 'Course deleted successfully'], 200);
  }
  public function DeleteClass(Request $request)
  {
    $id = $request['class_id'];
    $class = Classs::find($id);
    if (!$class) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Class not found'], 400);
    }
    $class->delete();
    return response()->json(['message' => 'Class deleted successfully'], 200);
  }

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

    return response()->json(['message' => 'Student info updated successfully'], 200);
  }
  public function AddAdvertisment(AdvertismentRequest $request)
  {
    $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());

    //$id= $request->validated()['advertisment_type_id'];
    $newadd = Advertisment::firstOrCreate([
      'title' => $request->validated()['title'],
      'image' => $upload,
      'description' => $request->validated()['description'],
      'tips' => $request->validated()['tips'],
      'is_shown' => $request->validated()['is_shown'],
      'advertisment_type_id' => $request->validated()['advertisment_type_id']

    ]);


    return response()->json(['message' => 'Class added successfully'], 200);
  }

  public function EditAdvertisment(Request $request)
  {
    $id = $request['advertisment_id'];
    $advertisment = Advertisment::findOrFail($id);
    if (!$advertisment) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Class not found'], 400);
    }
    if ($request->has('title')) {
      $advertisment->title = $request['title'];
    }
    if ($request->has('description')) {
      $advertisment->description = $request['description'];
    }
    if ($request->has('tips')) {
      $advertisment->tips = $request['tips'];
    }
    if ($request->has('is_shown')) {
      $advertisment->is_shown = $request['is_shown'];
    }
    if ($request->has('advertisment_type_id')) {
      $advertisment->advertisment_type_id = $request['advertisment_type_id'];
    }

    if ($request->hasFile('image')) {
      $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $advertisment->image = $upload;
    }

    $advertisment->save();

    return response()->json(['message' => 'Advertisment updated successfully'], 200);
  }

  public function DeleteAdvertisment(Request $request)
  {
    $id = $request[' '];
    $advertisment = Advertisment::findOrFail($id);
    if (Storage::exists($advertisment->image)) {
      Storage::delete($advertisment->image);
    }

    $advertisment->delete();

    return response()->json(['message' => 'Advertisment deleted successfully'], 200);
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
      return response()->json(['message' => 'Schedule Created Successfully'], 200);
    }
    return response()->json(['message' => 'Schedule Created Error'], 400);
  }

  public function ScheduleTeacher(TeacherScheduleRequest $request)
  {
    $TeacherId = $request->validated()['teacher_id'];
    $teacher = Teacher::find($TeacherId);
    if ($teacher) {
      $newSchedule = Teacher_Schedule::Create([
        'teacher_id' => $TeacherId,
        'day' => date('Y-m-d', strtotime($request->validated()['day'])),
       'start_time' => date('H:i:s', strtotime($request->validated()['start_time'])),
        'end_time' =>  date($request->validated()['end_time']),

      ]);
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

    return response()->json(['message' => 'Schedule deleted successfully'], 200);
  }

  public function deleteScheduleClass(Request $request)
  {

    $schedule = Class_Schedule::find( $request['schedule_id'])->get();


    if (!$schedule) {
      return response()->json(['message' => 'Schedule not found'], 400);
    }

    $schedule->delete();

    return response()->json(['message' => 'Schedule deleted successfully'], 200);
  }
}
