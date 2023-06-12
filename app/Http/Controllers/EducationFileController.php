<?php

namespace App\Http\Controllers;

use App\Models\LogFile;
use App\Models\FileTypes;
use App\Models\CourseName;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use App\Models\EducationFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\EducationFileRequest;

class EducationFileController extends Controller
{

    public function createFileTypes(Request $request){
      FileTypes::FirstOrCreate([
      'name'=>$request['name']
      ]);
      return response()->json(['message'=>'Type Added Successfully'],200);
    }



    public function uploadEducationFile(EducationFileRequest $request){
        $file = $request->file('file')->move('files/', $request->file('file')->getClientOriginalName());
        $course_id=$request->validated()['course_id'];
        $types=$request->validated()['file_types_id'];
        $course_find=CourseName::find($course_id);
        $type=FileTypes::find($types);
        if( $course_find&&$type){
            
      $file=EducationFile::FirstOrCreate([
        'course_id' => $course_id,
        'file_types_id' =>$types,
        'file' => $file,
    
      ]); 
       $user = JWTAuth::parseToken()->authenticate();
      $log = new LogFile();
          $log->user_id = $user->id;
          $log->action = 'Upload Education File';
          $log->save();
      
      }}
      public function deleteEducationFile($id){
      $education_file=EducationFile::find($id);
      $education_file->delete();
      $user = JWTAuth::parseToken()->authenticate();
      $log = new LogFile();
          $log->user_id = $user->id;
          $log->action = 'Delete Education File';
          $log->save();
      
      return response()->json(['message'=>'Education File Deleted Successfully'],200);

      }
      public function getEducationFile(Request $request){
        $course_name_id=$request['course_name_id'];
        $education_files=EducationFile::where('course_id',$course_name_id)->get();
        $education_files_type= $education_files->with('files')->get();
       
        return response()->json($education_files_type,200);
}

public function test1(Request $request){
 
  $education_files=FileTypes::with('files')->get();
  return $education_files;
}
public function getEducationFileById(Request $request){
$file_id=$request['file_id'];
$file=EducationFile::find($file_id);
if(!$file)
return response()->json(["message"=>"file does not exist"],400);
else 
return response()->json($file,200);

}}