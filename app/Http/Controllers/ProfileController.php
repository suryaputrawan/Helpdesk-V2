<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit()
    {
        try {
            $id = Auth::user()->id;
            $data = User::where('id', $id)->first();

            if (!$data) {
                return redirect()
                    ->back()
                    ->with('error', "Data tidak ditemukan");
            }

            return view('profile.edit', [
                'btnSubmit' => 'Update',
                'data'      =>  $data,
                'office'    =>  Office::orderBy('name')->get(),
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|min:3|max:255',
            'email'     => 'required|email|unique:users,email,' . auth()->user()->id,
            'office_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($id);
            $data = User::where('id', $id)->first();

            if (!$data) {
                return redirect()
                    ->back()
                    ->with('error', "Data tidak ditemukan");
            }

            $data->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'office_id' => $request->office_id,
            ]);

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Data profile berhasil diperbaharui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($request->current_password) {
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                throw ValidationException::withMessages([
                    'current_password'  =>  'Kata sandi saat ini tidak sesuai',
                ]);
            }
        }

        try {
            auth()->user()->update([
                'password'  =>  Hash::make($request->password),
            ]);

            // return redirect()->route('logout')->with('success', 'Password telah berhasil diperbaharui');

            return back()->with('success', 'Password telah berhasil diperbaharui');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }
}
