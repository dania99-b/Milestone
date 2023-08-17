<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Course;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\CourseName;
use App\Models\Reservation;
use App\Models\Advertisment;
use App\Models\CourseResult;
use Illuminate\Http\Request;
use App\Models\CourseAdvertisment;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\NotificationRecieved;
use App\Http\Requests\CourseRequest;
use App\Models\Notification;
use App\Notifications\WebSocketSuccessNotification;

class ReservationConrtoller extends Controller
{
    public function CheckBeforeReservation( $request)
    {
       
        $addvertisment = Advertisment::find($request);
        $addvertisment->course_id;
        $name_id = Course::find($addvertisment->course_id)->course_name_id;
        $name = CourseName::find($name_id);
        $courses = CourseName::all();
        $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
        $check = CourseResult::where('student_id', $student)->latest()->first();
       
        if ($check ) {
         
            $after6 = $check->created_at->addMonths(6);
            $currentDate = Carbon::now();
            if($currentDate <= $after6){
            
          
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
        }
        else{ return response()->json(['message' => 'sorry You should Do New Placement Test Youe Reach Expire Date'], 400);} 
     } else if (!$check) {
        
            $placement = \App\Models\StudentPlacement::where('student_id', $student)->latest()->first();
            $placementDate = Carbon::parse($placement->created_at);
            $after6 = $placementDate->addMonths(6);
            $currentDate = Carbon::now();

            if ($currentDate <= $after6) {
             
                $level = $placement->level;
                
                if ($level) {
                 
                    if ($name->name == $level) {
                        
                      
                        return $name;}
            } else return null;
        }
    }}
    public function makeReservation(Request $request )
    {
        $user = User::find(JWTAuth::parseToken()->authenticate()->id);
        $student_id = $user->student->id;
        $function = $this->CheckBeforeReservation($request['add_id']);
        if ($function != null) {
            $the_courseName_id = CourseName::where('name', $function->name)->get()->pluck('id'); /// find course Name id
            $CourseId = Course::where('course_name_id', $the_courseName_id)->latest()->first()->id;
            $reservation=Reservation::where('student_id',$student_id)->first();
      
            if(!$reservation){
                
            $newreservation = Reservation::create([
                'student_id' => $student_id,
                'course_id' => $CourseId,
                
            ])->load('student.user');
            $user = JWTAuth::parseToken()->authenticate();
            $log = new LogFile();
                $log->user_id = $user->id;
                $log->action = 'Make Reservation';
                $log->save();
            return response()->json(['message' => 'reservation done successfully'] , 200);
        } else {
            return response()->json(['message' => 'sorry cannot make reservation!!'], 400);
        }
    }}


    public function approveReservation($id)
    {
        // $request_id = $request['request_id'];
        $requestfind = Reservation::find($id);
        if ($requestfind) {
            $course_id = $requestfind->course_id;
            $requestfind->status = "ACCEPTED";

            $approve = CourseResult::Create(
                [
                    'course_id' => $course_id,
                    'student_id' => $requestfind->student_id
                ]
            );
            $student = Student::find($requestfind->student_id);
            if ($student) {
                $user = $student->user;
                $notificationHelper = new NotificationController();
                $msg = array(
                    'title' => 'Welcome !! ',
                    'body'  => 'Your Reservation Approved',
                );
                $notifyData = [
                    'title' => 'Welcome !!',
                    'body'  => 'Your Reservation Approved',
                ];
           

                foreach ($user->fcmtokens as $fcmtoken) {
                    $notificationHelper->send($fcmtoken->fcm_token, $msg, $notifyData);
                }
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->title = implode(', ', $msg);
                $notification->body = implode(', ', $notifyData);
                $notification->save();
            }
            $requestfind->delete();
            $user = JWTAuth::parseToken()->authenticate();
            $log = new LogFile();
                $log->user_id = $user->id;
                $log->action = 'Approve Reservation';
                $log->save();
            return response()->json(['message' => 'Reservation Approved Successfully'], 200);
          
        } else {
            return response()->json(['message' => 'Filed Approve Reservation'], 400);
        }
    }

    public function getAllReservation(){
        $reservation=Reservation::with('student.user')->with('course.courseName')->get();
        return response()->json($reservation,200);
    }
}
