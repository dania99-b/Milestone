<?php

namespace App\Http\Controllers;
use App\Models\Advertisment;
use Illuminate\Http\Request;
use App\Models\LogFile;


class AdvertismentController extends Controller
{
    public function list(){
        $ads = Advertisment::all();
        return response()->json($ads, 200);
    }

    public function create(AdvertismentRequest $request){
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $newadd = Advertisment::firstOrCreate([
            'title' => $request->validated()['title'],
            'image' => $upload,
            'description' => $request->validated()['description'],
            'tips' => $request->validated()['tips'],
            'is_shown' => $request->validated()['is_shown'],
            'advertisment_type_id' => $request->validated()['advertisment_type_id']
        ]);
        return response()->json(['message' => 'Class added successfully'], 200);
    }

    public function update(Request $request){
        $id = $request['advertisment_id'];
        $advertisment = Advertisment::findOrFail($id);
        if (!$advertisment) {
            return response()->json(['message' => 'Class not found'], 400);
        }
        if ($request->has('title')) {
        $advertisment->title = $request['title'];
        }
        if ($request->has('description')) {
        $advertisment->description = $request['description'];
        }
        if ($request->has('tips')) {
        $advertisment->tips = $request['tips'];
        }
        if ($request->has('is_shown')) {
        $advertisment->is_shown = $request['is_shown'];
        }
        if ($request->has('advertisment_type_id')) {
        $advertisment->advertisment_type_id = $request['advertisment_type_id'];
        }
        if ($request->hasFile('image')) {
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $advertisment->image = $upload;
        }
        $advertisment->save();
        $user=Auth::user();
        $employee=$user->employee;
        $log = new LogFile();
        $log->employee_id= $employee->id;
        $log->action = 'Edit Advertisment';
        $log->save();
        return response()->json(['message' => 'Advertisment updated successfully'], 200);
    }

    public function delete(Request $request){
        $id = $request[' '];
        $advertisment = Advertisment::findOrFail($id);
        if (Storage::exists($advertisment->image)) {
            Storage::delete($advertisment->image);
        }
        $advertisment->delete();
        return response()->json(['message' => 'Advertisment deleted successfully'], 200);
    }
}
