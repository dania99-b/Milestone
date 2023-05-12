<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;

use App\Models\CourseName;
use App\Models\CourseResult;
use App\Models\Student;



use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationConrtoller extends Controller
{
   public function CheckBeforeReservation(){
 
    
    $courses=['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B'];
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
        
        $placement=\App\Models\StudentPlacement::where('student_id',$student)->get()->first();
        $placementDate = Carbon::parse($placement->created_at);
        $after6=$placementDate->addMonths(6);
        $currentDate = Carbon::now();
     
        if($currentDate<=$after6){
           
            $level=$placement->level;
            if($level){
               
                foreach ($courses as $course){
                    if ($course==$level){
                       
                        $result=$course; /// the same element
            }
        
    }
  print($result);
}
    else return false;
   
  
}
   }}
   public function makeReservation(Request $request){
$function=$this->CheckBeforeReservation();
if( $function!=false){

}





}} 