<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\Day;
use App\Models\Classs;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Models\Course_Day;

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
 
  
    }}
  public function OpenClass(ClassRequest $request)
  {
    $newClass = Classs::firstOrCreate([
      'name' => $request->validated()['name'],
      'max_num' => $request->validated()['max_num'],
      'status' => $request->validated()['status'],
    ]);
  }
  public function EditClass(Request $request)
  {

    $class_name = $request['prev_name'];
    $class_id = Classs::where('name', $class_name)->first()->id;
    $class = Classs::find($class_id);
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
  }

  public function DeleteCourse(Request $request)
  {
    $id = $request['course_id'];
    $course = Course::find($id);
    $course->delete();
  }
  public function DeleteClass(Request $request){
    $id = $request['class_id'];
    $class = Classs::find($id);
    $class->delete();

  }
}
