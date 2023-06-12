<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Day;

use App\Models\Role;
use App\Models\Course;
use App\Models\LogFile;
use App\Models\CourseName;
use App\Models\Advertisment;
use App\Models\Notification;
use Illuminate\Http\Request;
use function PHPSTORM_META\map;

use App\Models\CourseAdvertisment;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Events\NotificationRecieved;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AdvertismentRequest;
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

          // Trigger a Pusher event
    //$pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
    //    'cluster' => env('PUSHER_APP_CLUSTER'),
     //   'useTLS' => true,
    //]);

   // $pusher->trigger('notification', 'new-advertisement', $upload);
   $user = JWTAuth::parseToken()->authenticate();
   $log = new LogFile();
   $log->user_id= $user->id;
   $log->action = 'Add Advertisment';
   $log->save();
        if ($request['course_id']) {
            $course_info = Course::find($request['course_id']);
            return response()->json($course_info, 200);
        } 
        
        
        else   return response()->json(['message' => 'Advertisment added successfully'], 200);

        
    
     


    }

    public function update(Request $request, $id)
    {
        $advertisment = Advertisment::findOrFail($id);
    
        if (!$advertisment) {
            return response()->json(['message' => 'Class not found'], 400);
        }
    
        $advertisment->fill($request->only([
            'title',
            'description',
            'tips',
            'is_shown',
            'advertisment_type_id'
        ]));
    
        if ($request->hasFile('image')) {
            $advertisment->image = $request->file('image')->store('images');
        }
    
        $user = JWTAuth::parseToken()->authenticate();
    
        DB::transaction(function () use ($advertisment, $user) {
            $advertisment->save();
    
            $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Edit Advertisment';
            $log->save();
        });
    
        return response()->json(['message' => 'Advertisment updated successfully'], 200);
    }
    
    public function delete($id)
    {
       
        $advertisment = Advertisment::findOrFail($id);
        if (Storage::exists($advertisment->image)) {
            Storage::delete($advertisment->image);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $advertisment->delete();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Delete Advertisment';
            $log->save();
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
    public function getAdvertismentByType(Request $request){
        $id=$request['id'];
        $advertisments = Advertisment::where('advertisment_type_id', $id)->get();
  return response()->json($advertisments,200);

    }
}
