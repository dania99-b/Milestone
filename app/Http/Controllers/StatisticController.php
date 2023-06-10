<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
   public function getStudentNumber(){

$students=Student::all()->count();
return response()->json($students,200);


   }
}
