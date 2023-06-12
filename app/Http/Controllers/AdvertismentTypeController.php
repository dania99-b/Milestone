<?php

namespace App\Http\Controllers;
use App\Models\LogFile;
use Illuminate\Http\Request;
use App\Models\AdvertismentType;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class AdvertismentTypeController extends Controller
{
    public function list(){
        $types = AdvertismentType::all();
        return response()->json($types, 200);
    }

    public function create(Request $request){
        $newType = AdvertismentType::firstOrCreate([
            'name' => $request['name'],
        ]);
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Create Advertisment Type';
            $log->save();
        return response()->json(['message' => 'Type added successfully'], 200);
    }

    public function update(Request $request){
        $id = $request['advertisment_type_id'];
        $type = AdvertismentType::findOrFail($id);
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
        $log->action = 'Update advertisment Type';
        $log->save();
        return response()->json(['message' => 'Type updated successfully'], 200);
    }

    public function delete(Request $request){
        $id = $request[' '];
        $type = AdvertismentType::findOrFail($id);
        if($type){
            $type->delete();
        }
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Delete Advertisment Type';
            $log->save();
        return response()->json(['message' => 'Advertisment type deleted successfully'], 200);
    }
}
