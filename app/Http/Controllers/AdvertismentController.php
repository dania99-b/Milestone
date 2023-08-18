<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Day;

use App\Models\Role;
use App\Models\User;
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
use App\Http\Controllers\NotificationController;
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

        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->with('fcmtokens')->get();
       
        $notificationHelper = new NotificationController();
        $msg = array(

            'New Advertisement',
        );
        $notifyData = [
             'Check For New Addvertisment',
           
        ];
        
        foreach ($students as $student) {
          
            foreach ($student->fcmtokens as $fcmtoken) {
          
                $notificationHelper->send( $fcmtoken->fcm_token, $msg, $notifyData);
              
               
    
            }
            $notification = new Notification();
            $notification->user_id = $student->id;
            $notification->title = implode(', ', $msg);
            $notification->body = implode(', ', $notifyData);
            $notification->save();
        }
       
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $add=Advertisment::firstOrCreate([
            'title' => $request->validated()['title'],
            'image' => $upload,
            'description' => $request->validated()['description'],
            'tips' => $request->validated()['tips'],
            'is_shown' => $request->validated()['is_shown'],
            'advertisment_type_id' => $request->validated()['advertisment_type_id'],
            'course_id' => $request['course_id'],
            'published_at' => $request['published_at'],
            'expired_at' => $request['expired_at'],
        ]);
        // Trigger a Pusher event
   

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
         
                Storage::delete($advertisment->image);
                $advertisment->image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
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
        $advertisment = Advertisment::with('course')->find($id);
        
        // Decode the "days" field in the Course model
       $advertisment->course->days = collect(json_decode($advertisment->course->days))->map(function ($dayId) {
            return Day::find($dayId);
        });

       $courseName = CourseName::find($advertisment->course->course_name_id)->value('name');
        $advertisment->course->course_name = $courseName;
         $advertisment->course->start_hour = $advertisment->course->period->start_hour;
    $advertisment->course->end_hour = $advertisment->course->period->end_hour;

    unset($advertisment->course->period);

        return response()->json( $advertisment, 200);
    }
    public function getAdvertismentByType(Request $request){
        $id=$request['id'];
        $advertisments = Advertisment::where('advertisment_type_id', $id)->get();
  return response()->json($advertisments,200);

    }
}
