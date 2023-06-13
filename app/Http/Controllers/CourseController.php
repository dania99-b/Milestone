<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Classs;
use App\Models\Course;
use App\Models\Period;
use App\Models\LogFile;
use App\Models\Teacher;
use App\Models\FileTypes;
use App\Models\Attendence;
use App\Models\CourseName;
use Illuminate\Http\Request;
use App\Models\EducationFile;
use App\Models\CourseAdvertisment;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\CourseRequest;
use App\Models\CourseResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function list()
    {
        $courses = Course::with('courseName')->with('class')->with('teacher.employee.user')->with('period')->get();
        foreach ($courses as $course) {
            $course->days = collect(json_decode($course->days))->map(function ($dayId) {
                return Day::find($dayId);
            });
        }
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

        $class_id = $request->validated()['class_id'];
        $period_id =  $request->validated()['period_id'];
        $start_day = $request->validated()['start_day'];
        $end_day = $request->validated()['end_day'];
        $teacher_id = $request->validated()['teacher_id'];

        $class = Classs::with('periods')->find($class_id);
        $teacher = Teacher::with('periods')->find($teacher_id);

        $isAvailable = false;
        $isAvailable2 = false;


        $classPeriod = $class['periods'];



        $teachePeriod = $teacher['periods'];

        $periodobject = Period::find($period_id);
        DB::beginTransaction();

        try {

            foreach ($classPeriod as $one_class_period) {

                if ($one_class_period->pivot->period_id == $period_id && $one_class_period->pivot->is_occupied == 0) {

                    $isAvailable = true;

                    $one_class_period->pivot->is_occupied = true;



                    // Update the "schedule" column in the database with the updated JSON string
                    $one_class_period->pivot->save();
                    $class->save();
                    break;
                }
                $one_class_period->save();
            }


            if ($isAvailable) {
                foreach ($teachePeriod as $one_teacher_period) {
                    if ($one_teacher_period->pivot->period_id == $period_id && $one_teacher_period->pivot->is_occupied == 0) {

                        $isAvailable2 = true;
                        $one_teacher_period->pivot->is_occupied = 1;

                        $one_teacher_period->pivot->save();
                        $teacher->save();
                        break;
                    }
                    $one_teacher_period->save();
                }
            }


            if ($isAvailable && $isAvailable2) {
                $newCourse = Course::create([
                    'class_id' => $class_id,
                    'period_id' => $period_id,
                    'start_day' => $start_day,
                    'end_day' => $end_day,
                    'qr_code' => $request->validated()['qr_code'],
                    'course_name_id' => $request->validated()['course_name_id'],
                    'days' => json_encode($dayIds), // Encode the array of day IDs
                    'teacher_id' => $teacher_id,
                ]);

                DB::commit();
                $user = JWTAuth::parseToken()->authenticate();
                $log = new LogFile();
                    $log->user_id = $user->id;
                    $log->action = 'Create Course';
                    $log->save();
                return response()->json($newCourse, 200);
            }

            DB::rollback();
            return response()->json(["message" => "can not create course"], 400);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }



    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $updateData = $request->only(['name', 'period_id', 'start_day', 'course_name_id', 'end_day', 'status', 'qr_code', 'teacher_id']);

        if (!empty($updateData)) {
            $course->fill($updateData);
            $course->save();
        }

        if ($request->has('days')) {
            $days = $request->input('days');
            $dayIds = [];

            foreach ($days as $day) {
                $dayModel = Day::where('name', $day)->firstOrFail();
                $dayIds[] = $dayModel->id;
            }

            $course->days = json_encode($dayIds);
            $course->save();
        }
         if ($request->has('period_id')) {
        $course->period_id = $request->input('period_id');
        $course->save();
    }

        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
        $log->user_id= $user->id;
        $log->action = 'Edit Course';
        $log->save();

        return response()->json(['message' => 'Course updated successfully'], 200);
    }


    public function delete($id)
    {
        $course = Course::find($id);
        $course_result=CourseResult::where('course_id',$course->id)->get();
        if ($course&& !$course_result) {
            $course->delete();
            $user = JWTAuth::parseToken()->authenticate();
            $log = new LogFile();
                $log->user_id = $user->id;
                $log->action = 'Delete Course';
                $log->save();
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }
        return response()->json(['message' => 'Cant Delete This Course'], 400);
    }
    public function getAllCourseName()
    {
        $course_name = CourseName::all();
        return response()->json($course_name, 200);
    }
    public function getCourseseRequest(Request $request)
    {
        $coursesName = CourseName::all();
        return response()->json($coursesName, 200);
    }

    public function getCourseNameEducationFile($courseName_id)
    {
        $coursesName = CourseName::find($courseName_id);
        $education_files=EducationFile::with('types')->where('course_id',$coursesName->id)->get();
        return response()->json($education_files,200);
        $types = FileTypes::with(['files' => function ($query) use ($coursesName) {
            $query->where('course_id', $coursesName->id);
        }])->get();
        return response()->json($types, 200);
    }
    public function getperiod()
    {

        $period = Period::all();
        return response()->json($period, 200);
    }

    public function get_attendence(Request $request)
    {
        $course = $request['course_id'];
        $date = $request['date'];
        $formattedDate = date('Y-m-d', strtotime($date));

        $attendances = Attendence::where('course_id', $course)
            ->whereDate(DB::raw('DATE(created_at)'), '=', $formattedDate)
            ->get();
        return response()->json($attendances, 200);
    }

    public function getCourseById($id)
    {
        $courses = Course::with('period:id,start_hour,end_hour')
                        ->with("class:id,name")
                        ->with("courseName:id,name")
                        ->where('id', $id)
                        ->get();
    
        $courses = $courses->map(function ($course) {
            $course->name = $course->courseName->name;
            $course->days = collect(json_decode($course->days))->map(function ($dayId) {
                return Day::find($dayId);
            });
            $course->start_hour = $course->period->start_hour;
            $course->end_hour = $course->period->end_hour;
            $course->class_name = $course->class->name;
            unset($course->courseName);
            unset($course->period);
            unset($course->class);
            return $course;
        });
    
        return $courses; 
    }
    
}