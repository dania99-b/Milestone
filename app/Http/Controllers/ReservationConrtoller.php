<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
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
   public function CheckBeforeReservation(){
 
   
    $courses=CourseName::all();
    $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
    $check=CourseResult::where('student_id',$student)->get()->first();
    if($check){
        print('if check');
    //$current_course=$check->course_id;
    $current_course_name=CourseName::find($check->id)->get()->pluck('course_name');
    foreach ($courses as $course){
        if ($course==$current_course_name){
         $result=next($courses);//+1 the next element in array 
        if($result==false){
            print('you are passed the final level ');
        }
        else{
            print('you are moved');
        }
        }
    
    }
    }
    else if(!$check){
        
        $placement=\App\Models\StudentPlacement::where('student_id',$student)->latest()->first();
        $placementDate = Carbon::parse($placement->created_at);
        $after6=$placementDate->addMonths(6);
        $currentDate = Carbon::now();
     
        if($currentDate<=$after6){
           
            $level=$placement->level;
            if($level){
           
                foreach ($courses as $course){
               
                    if ($course->name==$level){
              
                        $result=$course; /// the same element
                      
            }
    }
return $result;
}
    else return null;
   
  
}
   }}
   public function makeReservation(){
    $user=User::find(JWTAuth::parseToken()->authenticate()->id);
    $student_id=$user->student->id;
$function=$this->CheckBeforeReservation();
if($function!=null){
$the_courseName_id=CourseName::where('name',$function->name)->get()->pluck('id'); /// find course Name id
$CourseId=Course::where('course_name_id',$the_courseName_id)->latest()->first()->id;


$newreservation=Reservation::create([
'student_id'=>$student_id,
'course_id'=>$CourseId
]);


}
else{
    return response()->json(['message' => 'sorry cannot make reservation'], 40);
}





}


public function approveReservation(Request $request){
$request_id=$request['request_id'];
$requestfind=Reservation::find($request_id);
$requestfind->status="ACCEPTED";
                                                                                                                                                                                                                                                                                                  





}} 