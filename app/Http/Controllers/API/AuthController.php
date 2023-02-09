<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //cek validation field
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|min:5|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'office_id' => 'required',
            'department_id' => 'required',
            'role' => 'required',
        ]);

        //return response if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //create user to tabel in database
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'office_id' => $request->office_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
        ]);

        //create token
        $token = $user->createToken('auth_token')->plainTextToken;

        //return response api
        return response()
            ->json([
                'message' => 'Success',
                'data' => new AuthResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
    }

    public function login(Request $request)
    {
        //check authentication email and password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        //search email in database table user
        $user = User::where('email', $request['email'])->firstOrFail();

        //create token to user login
        $token = $user->createToken('auth_token')->plainTextToken;

        //return response in API
        return response()
            ->json([
                'message' => 'Success',
                'data' => new AuthResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
    }

    public function logout()
    {
        // Revoke the token that was used to authenticate the current request
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ]);
    }
}
