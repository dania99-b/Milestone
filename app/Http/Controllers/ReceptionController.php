<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\User;
use App\Models\Classs;
use App\Models\Course;
use App\Models\Student;
use App\Models\Course_Day;
use Illuminate\Http\Request;
use App\Http\Requests\ClassRequest;
use App\Http\Requests\CourseRequest;

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

  public function EditStudentInfo(Request $request){
    $student_id=$request['student_id'];
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
