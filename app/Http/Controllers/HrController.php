<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return response()->json(["message"=>"Request Accepted Successfully"],200);
}

public function refuseRequest($id,Request $body){
  $request=LeaveAndResignation::find($id);
 $refuse_reason=$body["refuse_reason"];

  $request->setAttribute('status', 'Refused');
  $request->refuse_reason=$refuse_reason;
  $request->save();
  return response()->json(["message"=>"Request Refused Successfully"],200);
}
}