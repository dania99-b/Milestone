<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Reception;
use Illuminate\Http\Request;
use App\Models\Human_Resource;
use App\Http\Requests\ImageRequest;
use App\Models\HumanResource;
use App\Models\Image;
use App\Models\LogFile;

class AdminController extends Controller
{
    public function updateTeacher(Request $request){
        $teacherId = $request->input('teacher_id');
        $teacher = Teacher::with('employee.user')->find($teacherId);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
        $employee = $teacher->employee;
        $user = $employee->user;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $employee->image = $image;
        }
        $user->fill($request->only(['first_name', 'last_name', 'birth', 'email', 'phone', 'username']));
        if ($user->isDirty()) {$user->save();}
        if ($employee->isDirty()) {$employee->save();}
        return response()->json(['message' => 'Teacher info updated successfully'], 200);
    }
    public function updateReception(Request $request){
        $receptionId = $request->input('reception_id');
        $reception = Reception::with('employee.user')->find($receptionId);
        if (!$reception) {
            return response()->json(['message' => 'receprion not found'], 404);
        }
        $employee = $reception->employee;
        $user = $employee->user;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $employee->image = $image;
        }
        $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone', 'username']));
        if ($user->isDirty()) {$user->save();}
        if ($employee->isDirty()) {$employee->save();}
        return response()->json(['message' => 'Reception info updated successfully'], 200);
    }
    public function updateHR(Request $request){
        $hrId = $request->input('hr_id');
        $hr = HumanResource::with('employee.user')->find($hrId);
        if (!$hr) {
            return response()->json(['message' => 'HR not found'], 404);
        }
        $employee = $hr->employee;
        $user = $employee->user;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $employee->image = $image;
        }
        $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone', 'username']));
        if ($user->isDirty()) {$user->save();}
        if ($employee->isDirty()) {$employee->save();}
        return response()->json(['message' => 'HR info updated successfully'], 200);
    }
    public function allHR(){
   
        $HR = HumanResource::with('employee.user')->get();
        return response()->json($HR, 200);
    }
    public function allTeachers(){
   
        $teachers = Teacher::with('employee.user')->get();
        return response()->json($teachers, 200);
    }
    public function allReceptions(){
   
        $receptions = Reception::with('employee.user')->get();
        return response()->json($receptions, 200);
    }
    public function getLogFile(){
        $logfile=LogFile::with("employee.user")->get();
        return response()->json( $logfile);
    }
}