<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\LogFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list(){
        $courses = Course::all();
        return response()->json($courses, 200);
    }

    public function create(CourseRequest $request){
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
  
    public function update(Request $request){
        $course_id = $request['course_id'];
        $course = Course::find($course_id);
        if (!$course) {
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
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Edit Course';
        $log->save();
        return response()->json(['message' => 'Course updated successfully'], 200);
    }

    public function delete(Request $request){
        $id = $request['course_id'];
        $course = Course::find($id);
        if ($course) {
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }
        return response()->json(['message' => 'Course not found'], 400);
    }
}
