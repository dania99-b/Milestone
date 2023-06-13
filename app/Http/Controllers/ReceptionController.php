<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\User;
use App\Models\Guest;
use App\Models\Image;
use App\Models\Classs;
use App\Models\Course;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Reception;
use App\Models\Course_Day;
use Illuminate\Http\Request;
use App\Models\Class_Schedule;
use App\Models\GuestPlacement;
use App\Models\Teacher_Schedule;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\ClassRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdvertismentRequest;
use App\Http\Requests\ClassScheduleRequest;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Requests\TeacherScheduleRequest;

class ReceptionController extends Controller
{
  
  public function editProfile(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();
    $employee = $user->employee;
    $teacher=$employee->reciption;
   
    if (!$teacher) {
      return response()->json(['message' => 'Student not found'], 404);
  }

  if ($request->hasFile('image')) {
      $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $employee->image = $image;
  }

  $user = $employee->user;
  $user->fill($request->only(['first_name', 'last_name', 'birthdate', 'email', 'phone']));

  if ($user->isDirty()) {
      $user->save();
  }

  if ($employee->isDirty()) {
      $employee->save();
  }
  
  if ($request->has('period_id')) {
    $periodIds = $request->input('period_id');
    $teacher->periods()->sync($periodIds); // Sync the period_ids in the pivot table
    $teacher->save();
}

  $user = JWTAuth::parseToken()->authenticate();
  $log = new LogFile();
  $log->user_id= $user->id;
$log->action = 'Edit Own Profile';
$log->save();
  return response()->json(['message' => 'Reception info updated successfully'], 200);
}

  public function EditStudentInfo(Request $request)
  {
    $student_id = $request['student_id'];
    $student = Student::find($student_id);
    if (!$student) {
      // Return a 400 status code with an error message if the course cannot be found
      return response()->json(['message' => 'Student not found'], 400);
    }
    if ($request->has('image')) {
      $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $student->image = $upload;
      $student->save();
    }
    $user_id = $student->user_id;
    $user = User::find($user_id);
    if ($request->has('first_name')) {
      $user->first_name = $request->first_name;
      $user->save();
    }
    if ($request->has('last_name')) {
      $user->last_name = $request->last_name;
      $user->save();
    }
    if ($request->has('email')) {
      $user->email = $request->email;
      $user->save();
    }
    if ($request->has('phone')) {
      $user->phone = $request->phone;
      $user->save();
    }

    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
    $log->user_id= $user->id;
$log->action = 'Edit Student information';
$log->save();
    return response()->json(['message' => 'Student info updated successfully'], 200);
  }
  

  public function list(){
  
    $receptions = Reception::with('employee.user')->get();
    
    return response()->json($receptions, 200);
  }
  public function days(){
  
    $days = Day::all();
    
    return response()->json($days, 200);
  }
  public function UploadImage(ImageRequest $request){
    $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
    $mainuser = Image::firstOrCreate([
            'published_at' => $request->validated()['published_at'],
            'is_show' =>$request->validated()['is_show'],
            'image'=>$upload,
            'expired_at'=>$request->validated()['expired_at']
    ]);
    return response()->json(["successfully uploaded"], 200);

}

public function editImage(Request $request, $id)
{
$image = Image::findOrFail($id);

if ($request->hasFile('image')) {
    $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
    $image->image = $upload;
}


if ($request->has('published_at')) {
    $image->published_at = $request['published_at'];
}


if ($request->has('is_show')) {
    $image->is_show = $request['is_show'];
}
if ($request->has('expired_at')) {
    $image->expired_at = $request['expired_at'];
}


$image->save();

return response()->json(["message" => "Image updated successfully"], 200);
}
public function deleteImage( $id)
{
  $image = Image::find($id);
  if($image){
   $image->delete();
   return response()->json(['message'=>'Image Deleted Successfully'],200);

  }
  else 
  return response()->json(['message'=>'Image Not Found'],400);



}}
