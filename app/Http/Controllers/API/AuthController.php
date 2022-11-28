<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:191',
            'email' => 'bail|required|email|max:191|unique:users',
            'password' => 'bail|required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken($user->email . '_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully',
            'username' => $user->name,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password))
        {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
                'status' => 'error'
            ]);
        }

        // $token = $user->createToken($user->email . '_token')->plainTextToken;

        if($user->role_as == User::ROLE_ADMIN){
            $role = 'admin';
            $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
        }else{
            $role = '';
            $token = $user->createToken($user->email . '_token', [''])->plainTextToken;
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged in Successfully',
            'username' => $user->name,
            'token' => $token,
            'role' => $role
        ]);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged Out Successfully',
        ]);
    }
}