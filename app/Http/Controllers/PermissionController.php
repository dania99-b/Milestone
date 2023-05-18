<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
 use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function addRole(RoleRequest $request){
        $role = Role::create([
            'name' => $request->validated()['name'],
            'display_name' =>$request->validated()['display_name'], // optional
            'description' =>$request->validated()['description'], // optional
        ]);
        return response()->json(['message' => 'Role created sccuessfully'], 200);
    }
    public function addPermission(Request $request){
        Permission::create([
            'name' => $request['name'],
            'display_name' =>  $request['display_name'], // optional
            'description' =>  $request['description'], // optional
        ]);
        return response()->json(['message' => 'Permission created sccuessfully'], 200);
    }
}