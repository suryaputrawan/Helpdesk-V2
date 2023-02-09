<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showloginform()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $request['isaktif'] = 1;

        if (Auth::attempt($request->only('username', 'password', 'isaktif'))) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->with('error', 'Invalid username and password, or the account has been disabled..!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showregisterform()
    {
        // return view('auth.register');

        $department = Department::all();
        $office = Office::all();

        return view('user.create', compact('department', 'office'));
    }

    public function postregister(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:5|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'office_id' => 'required',
            'department_id' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'office_id' => $request->office_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
        ]);

        // $user->createToken('myToken')->plainTextToken;

        return redirect()->route('user.index')->with('success', 'Data success to created');
    }
}
