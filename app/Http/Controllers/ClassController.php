<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\ClassPeriod;
use App\Models\Classs;
use App\Models\LogFile;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function list(){
   
            $classes = Classs::with("periods")->get();
        
            
          
            return response()->json($classes, 200);
        }
        
    public function create(ClassRequest $request){

        $periods = $request->validated()['period_id'];
        $periodIds = [];
        

        foreach ($periods as $period) {
            $periodModel = Period::find($period);
         
            $periodIds[] = $periodModel->id;
        }
       
        $newClass = Classs::Create([
            'name' => $request->validated()['name'],
            'max_num' => $request->validated()['max_num'],
            'status' => $request->validated()['status'],
        ]);
        
        $newClass->save();
        foreach ($periodIds as $period) {
      
        $newperiod_class=ClassPeriod::Create([
            'class_id' => $newClass->id,
            'period_id' => $period,
            'is_occupied'=>0
        ]);
        $newperiod_class->save();
    }
        
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Opened a New Class';
        $log->save();
        return response()->json(['message' => 'Class added successfully'], 200);
    }
    
    public function update(Request $request,$id){
       
        $class = Classs::find($id);
        if (!$class) {
            return response()->json(['message' => 'Course not found'], 400);
        }
        if ($request->has('name')) {
            $class->name = $request->name;
            $class->save();
        }
        if ($request->has('max_num')) {
            $class->max_num = $request->max_num;
            $class->save();
        }
        if ($request->has('status')) {
            $class->status = $request->status;
            $class->save();
        }
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Edit Class';
        $log->save();
        return response()->json(['message' => 'Class updated successfully'], 200);
    }

    public function delete($id){
       
        $class = Classs::find($id);
        if (!$class) {
            return response()->json(['message' => 'Class not found'], 400);
        }
        $class->delete();
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Delete Class';
        $log->save();
        return response()->json(['message' => 'Class deleted successfully'], 200);
    }
    public function getClassById(Request $request){
            
    $class = Classs::with("periods")->where('id',$request['class_id'])->get();
    
    if ($class) {

        return response()->json($class, 200);
    } else {
        return response()->json(['error' => 'Class not found'], 404);
    }
}
}