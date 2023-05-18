<?php

namespace App\Http\Controllers;
use App\Models\AdvertismentType;
use App\Models\LogFile;
use Illuminate\Http\Request;

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
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Update advertisment type';
        $log->save();
        return response()->json(['message' => 'Type updated successfully'], 200);
    }

    public function delete(Request $request){
        $id = $request[' '];
        $type = AdvertismentType::findOrFail($id);
        if($type){
            $type->delete();
        }
        return response()->json(['message' => 'Advertisment type deleted successfully'], 200);
    }
}
