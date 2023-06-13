<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Image;
use App\Models\LogFile;
use App\Models\Teacher;
use App\Models\Employee;

use App\Models\Reception;
use Illuminate\Http\Request;
use App\Models\HumanResource;
use App\Models\Human_Resource;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\ImageRequest;

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
        if ($request->has('period_id')) {
            $periodIds = $request->input('period_id');
            $teacher->periods()->sync($periodIds); // Sync the period_ids in the pivot table
            $teacher->save();
        }


        $user->fill($request->only(['first_name', 'last_name', 'birth', 'email', 'phone', 'username']));
        if ($user->isDirty()) {$user->save();}
        if ($employee->isDirty()) {$employee->save();}

        return response()->json(['message' => 'Teacher info updated successfully'], 200);
    }
  
    public function UploadImage(ImageRequest $request){
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $mainuser = Image::firstOrCreate([
                'published_at' => $request->validated()['published_at'],
                'is_show' =>$request->validated()['is_show'],
                'image'=>$upload

        ]);
        return response()->json(["successfully uploaded"], 200);

}
    public function updateReception(Request $request)
    {
        $receptionId = $request->input('reception_id');
        $reception = Reception::with('employee.user')->find($receptionId);
        
        if (!$reception) {
            return response()->json(['message' => 'Reception not found'], 404);
        }
    
        $employee = $reception->employee;
        $user = $employee->user;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $employee->image = $image;
        }
    
        $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone', 'username']));
        
        if ($user->isDirty()) {
            $user->save();
        }
    
        if ($employee->isDirty()) {
            $employee->save();
        }
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
        $log->user_id= $user->id;
        $log->action = 'Edit Reception';
        $log->save();
    
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
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
        $log->user_id= $user->id;
        $log->action = 'Edit Hr';
        $log->save();
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
        $logfile=LogFile::with("user")->orderBy('created_at', 'desc')->get();
        return response()->json( $logfile);
    }

    public function searchInLogFile($email){
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $logFiles = LogFile::with('user')->where('user_id', $user->id)->get();
    
        return response()->json($logFiles, 200);

}
public function addRoleToUser($userId, $roleId)
{
    
    // Get the user by their ID
    $user = User::findOrFail($userId);

    // Get the role by its ID
    $role = Role::findOrFail($roleId);

    // Attach the role to the user
    $user->attachRole($role);

    return response()->json(['message' => 'Role added to the user successfully'], 200);
}




}