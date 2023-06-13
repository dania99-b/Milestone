<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\LogFile;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\LeaveAndResignation;

class HrController extends Controller
{
    public function getRequestById($id){
        $requests=LeaveAndResignation::find($id);
        return response()->json($requests,200);
      } 

      public function approveRequest($id){
        $request=LeaveAndResignation::find($id);
        $request->setAttribute('status', 'Accepted');
        $request->save();
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Approve Request';
            $log->save();
        
        return response()->json(["message"=>"Request Accepted Successfully"],200);
}

public function refuseRequest($id,Request $body){
  $request=LeaveAndResignation::find($id);
 $refuse_reason=$body["refuse_reason"];

  $request->setAttribute('status', 'Refused');
  $request->refuse_reason=$refuse_reason;
  $request->save();
  $user = JWTAuth::parseToken()->authenticate();
  $log = new LogFile();
      $log->user_id = $user->id;
      $log->action = 'Refuse Request';
      $log->save();
  
  return response()->json(["message"=>"Request Refused Successfully"],200);
}
public function getAllCV(){
$cv=Cv::all();
return response()->json($cv,200);
}
}