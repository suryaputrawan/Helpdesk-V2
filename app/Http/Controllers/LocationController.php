<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->type == 'datatable') {
            $data = Location::get();

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $editRoute       = 'location.edit';
                    $deleteRoute     = 'location.destroy';
                    $dataId          = Crypt::encryptString($data->id);
                    $dataDeleteLabel = $data->name;

                    $action = "";
                    $action .= '
                        <a class="btn btn-soft-warning waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($editRoute, $dataId) . '">
                            Edit
                        </a> ';

                    $action .= '
                        <button class="btn btn-soft-danger waves-effect waves-light delete-item" 
                            data-label="' . $dataDeleteLabel . '" data-url="' . route($deleteRoute, $dataId) . '">
                            Delete
                        </button> ';

                    $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                        ' . $action . '
                    </div>';
                    return $group;
                })
                ->addColumn('office', function ($data) {
                    return $data->office->name;
                })
                ->rawColumns(['action', 'office'])
                ->make(true);
        }
        return view('location.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $office = Office::get();

        return view('location.create', compact('office'));
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
            'name' => 'required|min:3|max:50',
            'office_id' => 'required'
        ]);

        DB::beginTransaction();

        try {
            Location::create([
                'name'      => $request->name,
                'office_id' => $request->office_id
            ]);

            DB::commit();

            return redirect()->route('location.index')
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
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $decrypt = Crypt::decryptString($id);
            $data = Location::find($decrypt);
            $office = Office::get();

            if (!$data) {
                return redirect()
                    ->back()
                    ->with('error', "Data tidak ditemukan");
            }

            return view('location.edit', [
                'location'  => $data,
                'office'    => $office
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
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name'      => 'required|min:3|max:50',
            'office_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $location->update([
                'name'      =>  $request->name,
                'office_id' => $request->office_id
            ]);

            DB::commit();
            return redirect()->route('location.index')
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
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = Location::find($id);

            if (!$data) {
                return response()->json([
                    'status'  => 404,
                    'message' => "Data tidak ditemukan!",
                ], 404);
            }

            $data->delete();

            return response()->json([
                'status'  => 200,
                'message' => "Data location berhasil dihapus",
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 500,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
            ], 500);
        }
    }
}
