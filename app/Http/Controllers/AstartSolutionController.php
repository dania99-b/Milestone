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
    $allreservation = Reservation::pluck('course_id');
    foreach ($allreservation as $reservation) {
        $courses = Course::where('id', $reservation)->get()->pluck('course_name_id');
        $course_name = CourseName::where('id', $courses)->get();

        $c[] = $course_name->value('id');
        $c = array_unique($c);
    }

    $teachers = Teacher::all();
    $classes = Classs::all();
    $course_names = $c;
    $days = Day::all();
    $periods = Period::all();
    $result = [];

    foreach ($course_names as $course_id) {
        // Select three random days for the sessions
        $random_days = $days->random(3);

        // Iterate over the selected days and generate sessions
        foreach ($random_days as $day) {
            // Select a random period for the session
            $period = $periods->random();

            // Iterate over teachers and classes to find valid solutions
            foreach ($teachers as $teacher) {
                foreach ($classes as $class) {
                    $is_valid = $this->validSolution($course_id, $class->id, $teacher->id, $day->id, $period->id);
                    $check_period_day = Solution::where('course_name_id', $course_id)->where('period_id', $period->id)->where('day_id', $day->id)->first();

                    if ($is_valid && !$check_period_day) {
                        $new_solution = Solution::create([
                            'class_id' => $class->id,
                            'teacher_id' => $teacher->id,
                            'period_id' => $period->id,
                            'day_id' => $day->id,
                            'course_name_id' => $course_id
                        ]);

                        $result[] = $new_solution;
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
