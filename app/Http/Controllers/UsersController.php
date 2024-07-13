<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::where('user_type', 'user')
            ->orderBy('id', 'desc')
            ->with('roles')
            ->get();
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
            'password' => 'required|same:cpassword',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->user_type = 'user';
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->father_name = $request->input('father_name');
        $user->mother_name = $request->input('mother_name');
        $user->address = $request->input('address');
        $user->password = Hash::make($request->input('password'));

        if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];
            $name = time().".".$ext;
            $img = Image::make($request->photo)->resize(300, 300);
            $upload_path = 'assets/images/users/';
            $image_url = $upload_path.$name;
            $img->save(public_path($image_url));
            $user->photo = $image_url;
        }

        $user->save();

        if (!empty($request->role_id)) {
            $user->assignRole($request->input('role_id'));
        }

        return $this->success(message: "User Created Successfully.", statusCode: Response::HTTP_CREATED);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            // $roles = Role::all();
            return response()->json([
                'user' => $user,
                // 'roles' => $roles,
                'status' => 'success'
            ]);
        } catch (ModelNotFoundException $exception) {
            return $this->error(message: 'User not found.', statusCode: Response::HTTP_NOT_FOUND);
        }
    }

    public function viewProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /*
     * Display user profile.
     */

    public function updateProfile(Request $request)
    {
        $user_id = $request->get('id');
        $user = User::find($user_id);

        $user->name = empty($request->get('name')) ? $user->name : $request->get('name');

        $user->email = empty($request->get('email')) ? $user->email : $request->get('email');
        /*$user->password = $request->get('password');*/
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');

        $user->save();

        $message = 'change saved';
        return response()->json($message);

    }

    // update profile for auth user

    public function checkOldPassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is correct.']);
        } else {
            return response()->json(['error' => 'Current password is incorrect.'], 422);
        }

    }

    // check current password

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    // change password of auth user

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::findOrFail($request->id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->father_name = $request->input('father_name');
            $user->mother_name = $request->input('mother_name');
            $user->address = $request->input('address');
            if (!empty($request->password)) {
                $user->password = Hash::make($request->input('password'));
            }
            if ($request->photo) {
                // Extract image extension from base64 encoded image
                $position = strpos($request->photo, ';');
                $sub = substr($request->photo, 0, $position);
                $ext = explode('/', $sub)[1];
                // Generate a unique file name using the current timestamp
                $name = time().".".$ext;
                // Resize and save the new image to the public directory
                $img = Image::make($request->photo)->resize(600, 600);
                $upload_path = 'assets/images/users/';
                $image_url = $upload_path.$name;
                $img->save(public_path($image_url));
                // Remove the previous image from the public directory
                if ($user->photo) {
                    $prev_image_path = public_path($user->photo);
                    if (file_exists($prev_image_path)) {
                        unlink($prev_image_path);
                    }
                }
                // Update the user's image attribute
                $user->photo = $image_url;
            }

            $user->save();
            if (!empty($request->role_id)) {
                DB::table('model_has_roles')->where('model_id', $request->id)->delete();
                $user->assignRole($request->input('role_id'));
            }
            return response()->json(['message' => 'User update successfully']);
        } catch (Exception $e) {
            return $this->error(
                message: "User not found.",
                statusCode: Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User delete successfully']);
    }
}
