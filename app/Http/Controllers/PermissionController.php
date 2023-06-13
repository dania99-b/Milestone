<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
 use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

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
    public function roles(){
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    
    public function deleteRole($id)
{
    $role = Role::find($id);

    // Check if any user has this role assigned
    $usersWithRole = User::whereHas('roles', function ($query) use ($id) {
        $query->where('id', $id);
    })->count();

    if ($usersWithRole > 0) {
        return response()->json(['message' => 'Cannot delete role. Some users have this role assigned.'], 400);
    }

    // No users have the role assigned, so it is safe to delete
    $role->delete();

    return response()->json(['message' => 'Role deleted successfully'], 200);
}

}