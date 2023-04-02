<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Human_Resource;
use App\Models\Reception;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function EditTeacherInfo(Request $request){
        $teacher_id=$request['teacher_id'];
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
          // Return a 400 status code with an error message if the course cannot be found
          return response()->json(['message' => 'Teacher not found'], 400);
        }
        $employee=Employee::find($teacher->employee_id);
        if ($request->has('image')) {
          $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
          $employee->image = $upload;
          $employee->save();
      }
      $user_id=$employee->user_id;
        $user = User::find($user_id);
        if ($request->has('first_name')){
            $user->first_name = $request->first_name;
            $user->save();
        }
        if ($request->has('last_name')){
            $user->last_name = $request->last_name;
            $user->save();
        }
        if ($request->has('email')){
            $user->email = $request->email;
            $user->save();
        }
        if ($request->has('phone')){
            $user->phone = $request->phone;
            $user->save();
        }
        if ($request->has('username')){
            $user->username = $request->username;
            $user->save();
        }
        return response()->json(['message' => 'Teacher info updated successfully'], 200);
    
      }

      public function EditReceptionInfo(Request $request){
        $reception_id=$request['reception_id'];
        $reception = Reception::find($reception_id);
        if (!$reception) {
          // Return a 400 status code with an error message if the course cannot be found
          return response()->json(['message' => 'Reception not found'], 400);
        }
        $employee=Employee::find($reception->employee_id);
        if ($request->has('image')) {
          $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
          $employee->image = $upload;
          $employee->save();
      }
      $user_id=$employee->user_id;
        $user = User::find($user_id);
        if ($request->has('first_name')){
            $user->first_name = $request->first_name;
            $user->save();
        }
        if ($request->has('last_name')){
            $user->last_name = $request->last_name;
            $user->save();
        }
        if ($request->has('email')){
            $user->email = $request->email;
            $user->save();
        }
        if ($request->has('phone')){
            $user->phone = $request->phone;
            $user->save();
        }
        if ($request->has('username')){
            $user->username = $request->username;
            $user->save();
        }
    
        return response()->json(['message' => 'Teacher info updated successfully'], 200);
    
      }

      public function EditHrInfo(Request $request){
        $hr_id=$request['hr_id'];
        $hr = Human_Resource::find($hr_id);
        if (!$hr) {
          // Return a 400 status code with an error message if the course cannot be found
          return response()->json(['message' => 'Reception not found'], 400);
        }
        $employee=Employee::find($hr->employee_id);
        if ($request->has('image')) {
          $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
          $employee->image = $upload;
          $employee->save();
      }
      $user_id=$employee->user_id;
        $user = User::find($user_id);
        if ($request->has('first_name')){
            $user->first_name = $request->first_name;
            $user->save();
        }
        if ($request->has('last_name')){
            $user->last_name = $request->last_name;
            $user->save();
        }
        if ($request->has('email')){
            $user->email = $request->email;
            $user->save();
        }
        if ($request->has('phone')){
            $user->phone = $request->phone;
            $user->save();
        }
        if ($request->has('username')){
            $user->username = $request->username;
            $user->save();
        }
    
        return response()->json(['message' => 'Teacher info updated successfully'], 200);
    
      }


}
