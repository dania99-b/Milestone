<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Day;
use App\Models\Classs;
use App\Models\Course;
use App\Models\LogFile;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Models\CourseAdvertisment;
use App\Models\CourseName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function list(){
        $courses = Course::all();
        foreach($courses as $course){}
        $course->days = collect(json_decode($course->days))->map(function ($dayId) {
            return Day::find($dayId);
        });
            
        return response()->json($courses, 200);
    }

    public function create(CourseRequest $request)
{
    $days = $request->validated()['days'];
    $dayIds = [];

    foreach ($days as $day) {
        $dayModel = Day::where('name', $day)->firstOrFail();
        $dayIds[] = $dayModel->id;
    }
    $startHourTime = Carbon::parse($request->validated()['start_hour'])->setDate(1970, 1, 1);
    $endHourTime = Carbon::parse($request->validated()['end_hour'])->setDate(1970, 1, 1);

    $newCourse = Course::create([
        'class_id' => $request->validated()['class_id'],
        'start_hour' => date('H:i:s', strtotime($request->validated()['start_hour'])),
        'end_hour' => date('H:i:s', strtotime($request->validated()['end_hour'])),
        'start_day' => $request->validated()['start_day'],
        'end_day' => $request->validated()['end_day'],
        'qr_code' => $request->validated()['qr_code'],
        'course_name_id' => $request->validated()['course_name_id'],
        'days' => json_encode($dayIds), // Encode the array of day IDs
    ]);
   
    // Additional operations if needed

    // Return the new course or perform any necessary further actions
    return $newCourse;
}

    
    

  
    public function update(Request $request,$id){
       
        $course = Course::find($id);
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

    public function delete($id){
       
        $course = Course::find($id);
        if ($course) {
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }
        return response()->json(['message' => 'Course not found'], 400);
    }
    public function getAllCourseName(){
        $course_name=CourseName::all();
        return response()->json($course_name,200);
}
public function getCourseseRequest(Request $request)
{
    $coursesName=CourseName::all();
    return response()->json($coursesName,200);
}}