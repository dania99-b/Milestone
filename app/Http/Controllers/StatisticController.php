<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseName;
use App\Models\Employee;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
   public function getStudentNumber(){

$students=Student::all()->count();
return response()->json($students,200);


   }
   public function getTeacherNumber(){
    $students=Teacher::all()->count();
return response()->json($students,200);
}

public function getEmployeeNumber(){
    $students=Employee::all()->count();
return response()->json($students,200);
}
public function getRateRequestInEachCourse(){
    $counter=0;
$course_name=CourseName::all();

for($i=0;$i<count($course_name);$i++){

$array[$i]=$course_name[$i]->id;
}
for($j=0;$j<count($array);$j++){
$courses=Course::where('course_name_id',$array[$j])->get();
$counter = 0;
foreach ($courses as $course) {
    $reservations = Reservation::where('course_id', $course->id)->get();
    $counter += count($reservations);
}

$result[$array[$j]] = $counter;
}

return response()->json($result, 200);
}

public function get





}