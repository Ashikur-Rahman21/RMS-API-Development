<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class RolePermissionController extends Controller
{
    // all role list
    function index()
    {
        $roles = Role::orderBy('id','desc')->get();
        return response()->json(['roles' => $roles], 200);
    }

    // all permission list
    function permissions() 
    {
        $categories = Permission::distinct()->pluck('category');
        $permissionsByCategory = [];
        foreach($categories as $category){
            $permissions = Permission::where('category', $category) 
                                        ->get(['id','name']);
            $permissionsByCategory[$category] = $permissions;
        }
        return response()->json($permissionsByCategory);
    }

    // newly store role and permission data
    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Fetch permissions by their IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'guard_name'=>'sanctum'
        ]);

        // Sync permissions with the role
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Role created successfully'
        ], 200);
    }

    // get role and permission data to edit page
    function editRole(Role $role)
    {        
        $permissions = Permission::all();
        $rolePermissions = $role->permissions;

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'selectedPermissions' => $rolePermissions->pluck('name')
        ]);
    }
    // update role and permission 
    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id', // Ensure each permission ID exists
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Fetch the role by ID
        $role = Role::findOrFail($id);

        // Update the role's name
        $role->name = $request->name;
        $role->save();

        // Fetch permissions by their IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Sync permissions with the role
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Role updated successfully'
        ], 200);
    }
   
    // remove role data from database
    function destroy($id)
    {
        $role = Role::where('id',$id);
        $role->delete();
        return response()->json(['message' => 'Role delete successfully']);
    }
}
