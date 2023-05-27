<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationFile;
use App\Http\Requests\EducationFileRequest;
use App\Models\CourseName;
use App\Models\FileTypes;
use GuzzleHttp\Psr7\Message;

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
      
      }}
      public function deleteEducationFile($id){
      $education_file=EducationFile::find($id);
      $education_file->delete();
      return response()->json(['message'=>'Education File Deleted Successfully'],200);

      }
      public function getEducationFile(Request $request){
        $course_name_id=$request['course_name_id'];
        $education_files=EducationFile::where('course_id',$course_name_id)->get();
        return response()->json($education_files,200);
}
}