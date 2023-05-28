<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Course;

use App\Models\LogFile;
use App\Models\CourseName;
use App\Models\Advertisment;
use App\Models\Notification;
use Illuminate\Http\Request;
use function PHPSTORM_META\map;
use App\Models\CourseAdvertisment;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Events\NotificationRecieved;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdvertismentRequest;
use App\Models\Role;
use App\Notifications\WebSocketSuccessNotification;

class AdvertismentController extends Controller
{
    public function list()
    {
        $ads = Advertisment::all();
        return response()->json($ads, 200);
    }

    public function create(AdvertismentRequest $request)
    {

        $students = \App\Models\Role::where('name', 'student')->first()->users;
       
            // Send notification to each user with the "student" role
            foreach ($students as $student) {
                $student->notify(new WebSocketSuccessNotification('New Advertisment!'));
                event(new NotificationRecieved($student));
            }
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        Advertisment::firstOrCreate([
            'title' => $request->validated()['title'],
            'image' => $upload,
            'description' => $request->validated()['description'],
            'tips' => $request->validated()['tips'],
            'is_shown' => $request->validated()['is_shown'],
            'advertisment_type_id' => $request->validated()['advertisment_type_id'],
            'course_id' => $request['course_id'],
        ]);

        if ($request['course_id']) {
            $course_info = Course::find($request['course_id']);
            return response()->json($course_info, 200);
        } else   return response()->json(['message' => 'Advertisment added successfully'], 200);

        
    
     


    }

    public function update(Request $request,$id)
    {
       
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
        $user = JWTAuth::parseToken()->authenticate();
        $employee = $user->employee;
        $log = new LogFile();
        $log->employee_id = $employee->id;
        $log->action = 'Edit Advertisment';
        $log->save();
        return response()->json(['message' => 'Advertisment updated successfully'], 200);
    }

    public function delete($id)
    {
       
        $advertisment = Advertisment::findOrFail($id);
        if (Storage::exists($advertisment->image)) {
            Storage::delete($advertisment->image);
        }
        $advertisment->delete();
        return response()->json(['message' => 'Advertisment deleted successfully'], 200);
    }

    public function getActiveAds()
    {
        $now = Carbon::now();
        $advertisments = Advertisment::where('expired_at', '>', $now)->get();
        return response()->json($advertisments, 200);
    }
    public function getAdvertismentById(Request $request)
    {
        $id=$request['advertisment_id'];
        $advertisment = Advertisment::find($id)->load('course');

        // Decode the "days" field in the Course model
        $advertisment->course->days = collect(json_decode($advertisment->course->days))->map(function ($dayId) {
            return Day::find($dayId);
        });
       
    
        $courseName = CourseName::find($advertisment->course->course_name_id)->latest()->value('name');
        $advertisment->course->course_name = $courseName;
        return response()->json( $advertisment, 200);
    }
}
