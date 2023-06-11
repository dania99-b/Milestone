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
use Ramsey\Uuid\Type\Integer;
use SebastianBergmann\CodeCoverage\Report\Xml\Source;

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

                if ($periods_of_class->is_occupied == 0) {
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
                if ($periods_of_teacher->is_occupied == 0) {
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

    public function getPossibleSolution()
{
    $allReservations = Reservation::pluck('course_id');
    $courseNames = [];
    
    foreach ($allReservations as $reservation) {
        $courses = Course::where('id', $reservation)->pluck('course_name_id')->toArray();
        $courseNames = array_merge($courseNames, $courses);
    }

    $courseNames = array_unique($courseNames);
    $teachers = Teacher::all();
    $classes = classs::all();
    $course_names = $courseNames;
    $days = [1, 3, 5]; // Modify this array to [2, 4, 6] for 2, 4, 6 alternating days
    $periods = Period::all();
    $result = [];

    foreach ($days as $day) {
        for ($z = 0; $z < count($periods); $z++) {
            for ($j = 0; $j < count($classes); $j++) {
                for ($y = 0; $y < count($teachers); $y++) {
                    for ($x = 0; $x < count($course_names); $x++) {
                        $is_valid = $this->validSolution($course_names[$x], $classes[$j]->id, $teachers[$y]->id, $day, $periods[$z]->id);
                        $checkPeriodDay = Solution::where('course_name_id', $course_names[$x])
                            ->where('day_id', $day)
                            ->first();

                        if ($is_valid && !$checkPeriodDay) {
                            $newSolution = Solution::create([
                                'class_id' => $classes[$j]->id,
                                'teacher_id' => $teachers[$y]->id,
                                'period_id' => $periods[$z]->id,
                                'day_id' => $day,
                                'course_name_id' => $course_names[$x]
                            ]);

                            $result[] = $newSolution;
                            break; // Break the loop once a valid solution is found for the current day
                        }
                    }
                }
            }
        }
    }

    return $result;
}


    public function ProcessWorkDayConstraints()
    { //solution is list of object
        $WhichDay = 0;
        $cost = 0;
        $arr = array_fill(0, 6, 0);
        $check = 0;
        $result = $this->getPossibleSolution();
        $uniqueResults = [];
        $teacherIds = [];
        foreach ($result as $solution) {
            $teacherId = $solution->teacher_id;
            if (!in_array($teacherId, $teacherIds)) {
                $uniqueResults[] = $solution;
                $teacherIds[] = $teacherId;
            }
        for ($i = 0; $i < count($teacherIds); $i++) {
            $teacher_in_solution_id = $result[$i]->teacher_id;

            $TeacherPeriod = Teacher::with('periods')->find($teacher_in_solution_id);
            $periods = $TeacherPeriod['periods'];

            //$unique_periods=array_unique($periods);
            foreach ($periods as $period) {

                $whichDay = $period->id / 4;

                $arr[intval($whichDay)] = $arr[intval($whichDay)] + 1;
            }

            for ($j = 0; $j < 6; $j++) {
                if ($arr[$j] == 0) {

                    $check = 1;
                    break;
                }
            }
        }
    }
}

    public function ProcessLongDayConstraints()
    { //solution is list of object

        $WhichDay = 0;
        $cost = 0;
        $arr = array_fill(0, 6, 0);
        $check = 0;
        $result = $this->getPossibleSolution();
        $uniqueResults = [];
        $teacherIds = [];
        foreach ($result as $solution) {
            $teacherId = $solution->teacher_id;

            if (!in_array($teacherId, $teacherIds)) {
                $uniqueResults[] = $solution;
                $teacherIds[] = $teacherId;
            }
        }

        for ($i = 0; $i < count($teacherIds); $i++) {
            $teacher_in_solution_id = $result[$i]->teacher_id;

            $TeacherPeriod = Teacher::with('periods')->find($teacher_in_solution_id);
            $periods = $TeacherPeriod['periods'];

            //$unique_periods=array_unique($periods);
            foreach ($periods as $period) {

                $whichDay = $period->id / 4;

                $arr[intval($whichDay)] = $arr[intval($whichDay)] + 1;
            }

            for ($j = 0; $j < 6; $j++) {
                if ($arr[$j] > 5) {

                    $cost += 1;
                }
            }
        }
        return $cost;
    }

    public function ProcessNoSpaceConstraints()
    {

        $WhichDay = 0;
        $cost = 0;
        $arr = array_fill(0, 6, 0);
        $check = 0;
        $result = $this->getPossibleSolution();
        $uniqueResults = [];
        $teacherIds = [];
        foreach ($result as $solution) {
            $teacherId = $solution->teacher_id;

            if (!in_array($teacherId, $teacherIds)) {
                $uniqueResults[] = $solution;
                $teacherIds[] = $teacherId;
            }
        }

        for ($i = 0; $i < count($teacherIds); $i++) {
            $teacher_in_solution_id = $result[$i]->teacher_id;

            $TeacherPeriod = Teacher::with('periods')->find($teacher_in_solution_id);
            $periods = $TeacherPeriod['periods'];

            //$unique_periods=array_unique($periods);
            for ($i = 0; $i < count($periods); $i++) {
                if ($periods[$i]->id % 4 != 0) {
                    $distence = $periods[$i]->id - $periods[$i - 1]->id;
                    $LB = $periods[$i]->id / 4;
                    if ($distence == 2 && $periods[$i - 1]->id >= $LB) {
                        $cost = $cost + $distence;
                    }
                }
            }
        }

        return $cost;
    }
}
