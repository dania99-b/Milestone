<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Advertisment;
use App\Models\Course;
use App\Models\CourseAdvertisment;
use App\Models\CourseName;
use App\Models\CourseResult;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationConrtoller extends Controller
{
    public function CheckBeforeReservation($advertisment_id)
    {

    
        $addvertisment = Advertisment::find($advertisment_id);
        $addvertisment->course_id;
        $name_id = Course::find($addvertisment->course_id)->course_name_id;
        $name = CourseName::find($name_id);
        $courses = CourseName::all();
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
        $check = CourseResult::where('student_id', $student)->get()->first();
        if ($check) {

          
            $current_course = Course::find($check->course_id)->course_name_id;
            $current_course_name = CourseName::find($current_course);

            foreach ($courses as $index => $course) {

                if ($course == $current_course_name) {

                    $result = $index + 1;
                    $next_course = isset($courses[$result]) ? $courses[$result] : null;



                    if ($next_course->name == $name->name)

                        $bool = true;

                    else $bool = false;


                    if ($bool == false) {
                        print('you can not reserve in this level');
                    } else {

                        return $next_course;
                    }
                }
            }
        } else if (!$check) {

            $placement = \App\Models\StudentPlacement::where('student_id', $student)->latest()->first();
            $placementDate = Carbon::parse($placement->created_at);
            $after6 = $placementDate->addMonths(6);
            $currentDate = Carbon::now();

            if ($currentDate <= $after6) {

                $level = $placement->level;
                if ($level) {

                    if ($name == $level) {

                        $result = $name_id; /// the same element

                    }
                }
                return $result;
            } else return null;
        }
    }
    public function makeReservation(Request $request )
    {
        $user = User::find(JWTAuth::parseToken()->authenticate()->id);
        $student_id = $user->student->id;
        $function = $this->CheckBeforeReservation($request['add_id']);
        if ($function != null) {
            $the_courseName_id = CourseName::where('name', $function->name)->get()->pluck('id'); /// find course Name id
            $CourseId = Course::where('course_name_id', $the_courseName_id)->latest()->first()->id;


            $newreservation = Reservation::create([
                'student_id' => $student_id,
                'course_id' => $CourseId
            ]);
        } else {
            return response()->json(['message' => 'sorry cannot make reservation'], 400);
        }
    }


    public function approveReservation(Request $request)
    {
        $request_id = $request['request_id'];
        $requestfind = Reservation::find($request_id);
        if ($requestfind) {
            $course_id = $requestfind->course_id;
            $requestfind->status = "ACCEPTED";

            $approve = CourseResult::Create(
                [
                    'course_id' => $course_id,
                    'student_id' => $requestfind->student_id
                ]
            );
            return response()->json(['message' => 'Reservation Approved Successfully'], 200);
        } else {
            return response()->json(['message' => 'Filed Approve Reservation'], 400);
        }
    }
}
