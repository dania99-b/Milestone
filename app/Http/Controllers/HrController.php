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
}
