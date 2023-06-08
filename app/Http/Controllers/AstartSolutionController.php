<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Classs;
use App\Models\Course;
use App\Models\Period;
use App\Models\Teacher;
use App\Models\CourseName;
use App\Models\Reservation;
use App\Models\Solution;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\List_;

class AstartSolutionController extends Controller
{
    public function validSolution($coursename, $class, $teacher, $day, $period)
    {
        $classobject = Classs::find($class);
        $teacherobject = Teacher::find($teacher);
        $dayobject = Day::find($day);
        $periodobject = Period::find($period);
        

        $check = 0;
        $check1 = 0;

        if ($dayobject->is_vacation == 1)
            return false;

        if ($classobject->status != "AVAILABLE")


            return false;
        $number_student = $this->calcStudentNumbers($coursename);
        if ($classobject->max_num < $number_student)
            return false;
        //true if 0
        //false if 1
        if ($periodobject->is_available == 1)
            return false;

        $classperiod = Classs::with('periods')->find($class);
        $periods = $classperiod['periods'];
        foreach ($periods as $periods_of_class) {
           
            if ($periods_of_class->id == $period) {
      
                if ($periods_of_class->is_occupied== 0) {
                    $check = 1;
                    break;
                }
            } else continue;
        }
        if ($check == 0)
            return false;

        $teacherperiod = Teacher::with('periods')->find($teacher);
        $periods = $teacherperiod['periods'];
        foreach ($periods as $periods_of_teacher) {
            if ($periods_of_teacher->id == $period) {
                if ($periods_of_teacher->is_occupied== 0) {
                    $check1 = 1;
                    break;
                }
            } else continue;
        }
        if ($check1 == 0)
            return false;

        return true;
    }


    public function calcStudentNumbers($id)
    {
        $counter = 0;
        $course = Course::where('course_name_id', $id)->get()->pluck('id');

        foreach ($course as $one) {
            $counter = $counter + Reservation::where('course_id', $one)->count();
        }

        return $counter;
    }

    public function getPossibleSolution(){
       $allreservation= Reservation::pluck('course_id');
       foreach($allreservation as $reservation){
       $courses=Course::where('id',$reservation)->get()->pluck('course_name_id');
      $course_name=CourseName::where('id',$courses)->get();
     
     $c[]=$course_name->value('id');
     $c=array_unique($c);

    }
   
$teachers=Teacher::all();
$classes=classs::all();
$course_names=$c;
$days=Day::all();
$periods=Period::all();
for($i=0;$i<count($days);$i++){
  for($j=0;$j<count($classes);$j++){
    for($y=0;$y<count($teachers);$y++){
        for($z=0;$z<count($periods);$z++){
            for($x=0;$x<count($course_names);$x++){

        $is_valid=$this->validSolution($course_names[$x], $classes[$j]->id, $teachers[$y]->id, $days[$i]->id, $periods[$z]->id);
        
        if($is_valid==true){
$newslution=Solution::Create([
'class_id'=>$classes[$j]->id,
'teacher_id'=>$teachers[$y]->id,
'period_id'=>$periods[$z]->id,
'day_id'=> $days[$i]->id,
'course_name_id'=>$course_names[$x]

]);
$newslution->save();
        }

        }
    }
}
}
}

  
}
}


