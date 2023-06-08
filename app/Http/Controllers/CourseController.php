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
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function list(){
        $courses = Course::with('courseName')->with('class')->with('teacher.employee.user')->get();
        foreach($courses as $course){
        $course->days = collect(json_decode($course->days))->map(function ($dayId) {
            return Day::find($dayId);
        });}
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
        $start_hour = date('H:i', strtotime($request->validated()['start_hour']));
        $period_id =  $request->validated()['period_id'];
        $start_day = $request->validated()['start_day'];
        $end_day = $request->validated()['end_day'];
        $teacher_id = $request->validated()['teacher_id'];

        $class = Classs::find($class_id);
        $teacher = Teacher::find($teacher_id);

        $isAvailable = false;
        $isAvailable2 = false;

        
        $classSchedules = json_decode($class->schedules, true);
        $teacherSchedules = json_decode($teacher->schedules, true);

        DB::beginTransaction();

        try {
            foreach ($classSchedules as $key => $schedule) {
                if ($schedule['date'] === $start_day) {
                    $classStartHour = $schedule['start_hour'];
                    $classEndHour = $schedule['end_hour'];
/////////////////////////////////////////////////////////////
                    // Compare the hours
                    if ($start_hour >= $classStartHour && $end_hour <= $classEndHour) {


                        $isAvailable = true;

                        unset($classSchedules[$key]);
                        $classSchedulesJson = json_encode(array_values($classSchedules));

                        // Update the "schedule" column in the database with the updated JSON string
                        $class->schedules = $classSchedulesJson;
                        $class->save();
                        break;
                    }
                }
            }

            if ($isAvailable) {
                foreach ($teacherSchedules as $key => $Tschedule) {
                    if ($Tschedule['date'] === $start_day) {
                        $teacherStartHour = $Tschedule['start_hour'];
                        $teacherEndHour = $Tschedule['end_hour'];


                        if ($start_hour >= $teacherStartHour && $end_hour <= $teacherEndHour) {

                            $isAvailable2 = true;
                            unset($teacherSchedules[$key]);
                            $teacherSchedulesJson = json_encode(array_values($classSchedules));


                            $teacher->schedules = $teacherSchedulesJson;
                            $teacher->save();
                            break;
                        }
                    }
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

        $updateData = $request->only(['name', 'start_hour', 'end_hour', 'start_day', 'course_name_id', 'end_day', 'status', 'qr_code', 'teacher_id']);

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

        $user = Auth::user();
        $employee = $user->employee;

        $log = new LogFile();
        $log->employee_id = $employee->id;
        $log->action = 'Edit Course';
        $log->save();

        return response()->json(['message' => 'Course updated successfully'], 200);
    }


    public function delete($id)
    {

        $course = Course::find($id);
        if ($course) {
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully'], 200);
        }
        return response()->json(['message' => 'Course not found'], 400);
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
}
