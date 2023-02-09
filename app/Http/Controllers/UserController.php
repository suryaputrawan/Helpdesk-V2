<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->type == 'datatable') {
            $data = User::get();

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $editRoute       = 'user.edit';
                    $dataId          = Crypt::encryptString($data->id);

                    $action = "";
                    $action .= '
                        <a class="btn btn-soft-warning waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($editRoute, $dataId) . '">
                            Edit
                        </a> ';

                    $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                        ' . $action . '
                    </div>';
                    return $group;
                })
                ->addColumn('aktif', function ($data) {
                    return $data->isaktif == "1" ? "Aktif" : "Non Aktif";
                })
                ->rawColumns(['action', 'aktif'])
                ->make(true);
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $department = Department::get();
        $office = Office::get();

        return view('user.create', compact('department', 'office'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|numeric|min:5|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'office_id' => 'required',
            'department_id' => 'required',
            'role' => 'required',
        ]);

        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'office_id' => $request->office_id,
                'department_id' => $request->department_id,
                'role' => $request->role,
            ]);

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'Data was created');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $decrypt = Crypt::decryptString($id);
            $data = User::find($decrypt);
            $department = Department::get();
            $office = Office::get();

            if (!$data) {
                return redirect()
                    ->back()
                    ->with('error', "Data tidak ditemukan");
            }

            return view('user.edit', [
                'office'      => $office,
                'department'  => $department,
                'user'        => $data,
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|min:3',
            'username'      => 'required|min:3|unique:users,username,' . $user->id,
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'office_id'     => 'required',
            'department_id' => 'required',
            'role'          => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name'          => $request->name,
                'username'      => $request->username,
                'email'         => $request->email,
                'office_id'     => $request->office_id,
                'department_id' => $request->department_id,
                'role'          => $request->role,
                'isaktif'       => $request->isaktif,
            ]);

            DB::commit();
            return redirect()->route('user.index')
                ->with('success', 'Data was updated');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
