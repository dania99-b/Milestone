<?php

namespace App\Http\Controllers;
use App\Models\Classs;
use App\Models\LogFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function list(){
        $classes = Classs::all();
        return response()->json($classes, 200);
    }
    
    public function create(ClassRequest $request){
        $newClass = Classs::firstOrCreate([
            'name' => $request->validated()['name'],
            'max_num' => $request->validated()['max_num'],
            'status' => $request->validated()['status'],
        ]);
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Opened a New Class';
        $log->save();
        return response()->json(['message' => 'Class added successfully'], 200);
    }

    public function update(Request $request){
        $class_id = $request['class_id'];
        $class = Classs::find($class_id);
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

    public function delete(Request $request){
        $id = $request['class_id'];
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
}
