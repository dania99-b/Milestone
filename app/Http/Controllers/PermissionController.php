<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
 use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function AddRole(RoleRequest $request){
        
    $role = Role::create([

        'name' => $request->validated()['name'],
        'display_name' =>$request->validated()['display_name'], // optional
        'description' =>$request->validated()['description'], // optional
    ]);
}


public function add_permission(Request $request){
    Permission::create([
        'name' => $request['name'],
        'display_name' =>  $request['display_name'], // optional
        'description' =>  $request['description'], // optional
    ]);
}


}