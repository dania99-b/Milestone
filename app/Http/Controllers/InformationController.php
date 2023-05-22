<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogFile;
use App\Models\Information;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\InformationRequest;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class InformationController extends Controller
{
    public function store(InformationRequest $request)
    {
     
            $newInfo = Information::firstOrCreate([
                'who_we_are' => $request->validated()['who_we_are'],
                'contact_us' => $request->validated()['contact_us'],
                'services' => $request->validated()['services'],
               
            ]);

        return response()->json(['data' => "Store Successfully"], 200);
    }



    public function editInfo(Request $request)
    {
     
    $information = Information::find(1);
    $information->fill($request->only(['who_we_are', 'contact_us', 'services']));

    if ($information->isDirty()) {
        $information->save();
    }
  
    $user=User::find(JWTAuth::parseToken()->authenticate()->id)->id;
   
    $log = new LogFile();
  $log->employee_id= $user;
  $log->action = 'Edit App Information';
  $log->save();
    return response()->json(['message' => 'info updated successfully'], 200);
  }
  public function getInfo()
  {

    $info=Information::find(1);
    return response()->json($info, 200);
}}
