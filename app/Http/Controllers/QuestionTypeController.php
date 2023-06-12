<?php

namespace App\Http\Controllers;

use App\Models\LogFile;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class QuestionTypeController extends Controller
{
    public function list(){
        $types = QuestionType::all();
        return response()->json($types, 200);
    }

    public function AddType(Request $request){
        $type = QuestionType::firstOrCreate([
            'name' => $request['name'],
        ]);
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
        $log->user_id= $user->id;
        $log->action = 'Add Question Type';
        $log->save();
        return response()->json(['message' => 'Question type added successfully'], 200);
    }

    public function update(Request $request){
        $id = $request['question_type_id'];
        $type = QuestionType::findOrFail($id);
        if (!$type) {
            return response()->json(['message' => 'Type not found'], 400);
        }
        if ($request->has('name')) {
            $type->name = $request['name'];
        }
        $type->save();
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
        $log->user_id= $user->id;
        $log->action = 'Update question type';
        $log->save();
        return response()->json(['message' => 'Type updated successfully'], 200);
    }

    public function delete(Request $request){
        $id = $request[' '];
        $type = QuestionType::findOrFail($id);
        if($type){
            $type->delete();
        }
        return response()->json(['message' => 'Question type deleted successfully'], 200);
    }
}
