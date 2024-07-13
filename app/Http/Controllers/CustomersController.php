<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::where('user_type', 'customer')
            ->orderBy('id', 'desc')->get();
        return response()->json(['rows' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required:same:cpassword',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->user_type = 'customer';
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->father_name = $request->input('father_name');
        $user->mother_name = $request->input('mother_name');
        $user->address = $request->input('address');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        if (!empty($request->role_id)) {
            $user->assignRole($request->input('role_id'));
        }

        return response()->json(['message' => 'Supplier created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        // $roles = Role::all();

        return response()->json([
            'user' => $user,
            // 'roles' => $roles
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->father_name = $request->input('father_name');
        $user->mother_name = $request->input('mother_name');
        $user->address = $request->input('address');
        if (!empty($request->password)) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        if (!empty($request->role_id)) {
            DB::table('model_has_roles')->where('model_id', $request->id)->delete();
            $user->assignRole($request->input('role_id'));
        }
        return response()->json(['message' => 'Supplier update successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Supplier delete successfully']);
    }

}
