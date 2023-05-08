<?php

namespace App\Http\Controllers;

use App\Models\Course_Name;
use App\Models\Course_Result;
use App\Models\Student;
use App\Models\Student_Placement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationConrtoller extends Controller
{
   public function CheckBeforeReservation(){
    $result="";
    $courses=['1A','1B','2A','2B','3A','3B','4A','4B'];
    $student = Student::where('user_id', JWTAuth::parseToken()->authenticate()->id)->get()->first()->id;
    $check=Course_Result::where('student_id',$student)->get()->first();
    if($check){
    $current_course=$check->course_id;
    $current_course_name=Course_Name::find($check->id)->get()->pluck('course_name');
    foreach ($courses as $course){
        if ($course==$current_course_name){
         $result=$course;//+1 the next element in array 
        
        }
    
    }
    }
    else if(!$check){
        $placement=Student_Placement::where('student_id',$student)->get()->first();
        if($placement->created_at<Carbon::now()){//////////////////////
        $level=$placement->level;
        if($level){
            foreach ($courses as $course){
                if ($course==$level){
                    $result=$course; /// thw same element
        }
    }
    }
    else return "you not complete your oral placament test";
   }
}
   }}