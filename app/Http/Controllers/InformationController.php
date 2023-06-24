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
      $whoWeAre = substr($request->input('who_we_are'), 0, 255); // Adjust the length as per the column's current length
      $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());

     
            $newInfo = Information::firstOrCreate([
                'who_we_are' =>  $whoWeAre,
                'contact_us' => $request->validated()['contact_us'],
                'services' => $request->validated()['services'],
                'email' => $request->validated()['email'],
                'image' => $image,
               
            ]);


        return response()->json(['data' => "Store Successfully"], 200);
    }



    public function editInfo(Request $request)
    {
     
    $information = Information::find(1);
    $information->fill($request->only(['who_we_are', 'contact_us', 'services','email','image']));
    if ($request->hasFile('image')) {
      // Remove old image
      if ($information->image && file_exists($information->image)) {
          unlink($information->image);
      }

      // Upload new image
      $image = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
      $information->image = $image;
  }
    if ($information->isDirty()) {
        $information->save();
    }
  
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
    $log->user_id= $user->id;
  $log->action = 'Edit App Information';
  $log->save();
  $user = JWTAuth::parseToken()->authenticate();
  $log = new LogFile();
      $log->user_id = $user->id;
      $log->action = 'Edit Information';
      $log->save();
  
    return response()->json(['message' => 'info updated successfully'], 200);
  }
  public function getInfo()
  {

    $info=Information::find(1);
    return response()->json($info, 200);
}}
