<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Ticket;
use App\Mail\TicketMail;
use App\Models\Category;
use App\Models\ParamNumber;
use App\Models\TicketProgres;
use App\Mail\TicketUpdateMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\TicketProgressRequest;
use App\Models\Item;
use App\Models\ItemTicket;
use App\Models\Location;
use DateTime;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user()->id;
        $roleUser = auth()->user()->role == "User";
        $roleManagement = auth()->user()->role == "Management";
        $roleTechnician = auth()->user()->role == "Technician";
        $deptTechnician = auth()->user()->department_id;

        if ($roleUser) {
            if (request()->type == 'datatable') {
                $data = Ticket::where('requester_id', $user)->orderBy('id', 'desc')->get();

                return datatables()->of($data)
                    ->addColumn('action', function ($data) {
                        $showRoute      = 'ticket.show';
                        $closedRoute    = 'ticket.closed';
                        $dataId         = Crypt::encryptString($data->id);
                        $dataLabel      = $data->nomor;
                        $dataTitle       = $data->title;

                        $action = "";
                        if ($data->status->name == 'Solved') {
                            $action .= '
                            <a type="button" class="btn btn-soft-danger waves-effect waves-light me-1 closed-btn" 
                                data-label="' . $dataLabel . '"
                                data-title="' . $dataTitle . '" 
                                data-url="' . route($closedRoute, $dataId) . '">
                                Closed
                            </a> ';
                        }

                        $action .= '
                            <a class="btn btn-soft-primary waves-effect waves-light me-1" id="btn-show" type="button" href="' . route($showRoute, $dataId) . '">
                                Show
                            </a> ';

                        $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                        return $group;
                    })
                    ->editColumn('created_at', function ($data) {
                        return $data->created_at->isoFormat('D MMM Y');
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category->name;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status->name == 'Solved' || $data->status->name == 'Closed') {
                            return '<span style="color:red">' . $data->status->name . '</span>';
                        }
                        return '<span style="color:blue">' . $data->status->name . '</span>';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician != null ? $data->userTechnician->name : "";
                    })
                    ->rawColumns(['action', 'category', 'status', 'technician'])
                    ->make(true);
            }

            return view('ticket.user.index');
        } elseif ($roleTechnician) {
            $tNew = Ticket::where('department_id', $deptTechnician)
                ->where('assign', '=', 0)->get()->count();
            $tProgress = Ticket::where('technician_id', $user)
                ->where('status_id', '=', 2)
                ->where('assign', '=', 1)->get()->count();
            $tHold = Ticket::where('technician_id', $user)
                ->where('status_id', '=', 3)
                ->where('assign', '=', 1)->get()->count();
            $tSolved = Ticket::where('technician_id', $user)
                ->where('assign', '=', 1)
                ->where('solved_date', '!=', null)
                ->orWhere(function ($query) {
                    $query->where('status_id', '=', 4)
                        ->where('status_id', '=', 5);
                })
                ->get()->count();

            if (request()->type == 'datatable') {
                $data = Ticket::where('department_id', $deptTechnician)
                    ->where('assign', '=', 0)->get();

                return datatables()->of($data)
                    ->addColumn('action', function ($data) {
                        $assignRoute     = 'ticket.assign';
                        $dataId          = Crypt::encryptString($data->id);
                        $dataAssignLabel = $data->nomor;

                        $action = "";
                        $action .= '
                        <a type="button" class="btn btn-soft-danger waves-effect waves-light me-1 assign-btn" 
                            data-label="' . $dataAssignLabel . '" data-url="' . route($assignRoute, $dataId) . '">
                            Assign
                        </a> ';

                        $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                                ' . $action . '
                            </div>';
                        return $group;
                    })
                    ->editColumn('created_at', function ($data) {
                        return $data->created_at->isoFormat('D MMM Y');
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category->name;
                    })
                    ->addColumn('requester', function ($data) {
                        return $data->userRequester->name;
                    })
                    ->addColumn('office', function ($data) {
                        return $data->userRequester->office->name;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status->name == 'Solved' || $data->status->name == 'Closed') {
                            return '<span style="color:red">' . $data->status->name . '</span>';
                        }
                        return '<span style="color:blue">' . $data->status->name . '</span>';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician != null ? $data->userTechnician->name : "";
                    })
                    ->rawColumns(['action', 'category', 'status', 'technician', 'requester', 'office'])
                    ->make(true);
            }

            return view('ticket.technician.index', compact('tNew', 'tProgress', 'tHold', 'tSolved'));
        } elseif ($roleManagement) {
            if (request()->type == 'datatable') {
                $data = Ticket::where('requester_id', $user)->orderBy('id', 'desc')->get();

                return datatables()->of($data)
                    ->addColumn('action', function ($data) {
                        $showRoute      = 'ticket.show';
                        $closedRoute    = 'ticket.closed';
                        $dataId         = Crypt::encryptString($data->id);
                        $dataLabel      = $data->nomor;
                        $dataTitle      = $data->title;

                        $action = "";
                        if ($data->status->name == 'Solved') {
                            $action .= '
                            <a type="button" class="btn btn-soft-danger waves-effect waves-light me-1 closed-btn" 
                                data-label="' . $dataLabel . '"
                                data-title="' . $dataTitle . '" 
                                data-url="' . route($closedRoute, $dataId) . '">
                                Closed
                            </a> ';
                        }

                        $action .= '
                            <a class="btn btn-soft-primary waves-effect waves-light me-1" id="btn-show" type="button" href="' . route($showRoute, $dataId) . '">
                                Show
                            </a> ';

                        $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                        return $group;
                    })
                    ->editColumn('created_at', function ($data) {
                        return $data->created_at->isoFormat('D MMM Y');
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category->name;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status->name == 'Solved' || $data->status->name == 'Closed') {
                            return '<span style="color:red">' . $data->status->name . '</span>';
                        }
                        return '<span style="color:blue">' . $data->status->name . '</span>';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician != null ? $data->userTechnician->name : "";
                    })
                    ->rawColumns(['action', 'category', 'status', 'technician'])
                    ->make(true);
            }

            return view('ticket.user.index');
        } else {
            if (request()->type == 'datatable') {
                $data = Ticket::get();

                return datatables()->of($data)
                    ->addColumn('action', function ($data) {
                        $showRoute       = 'ticket.show';
                        $dataId          = Crypt::encryptString($data->id);

                        $action = "";
                        $action .= '
                            <a class="btn btn-soft-warning waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($showRoute, $dataId) . '">
                                Show
                            </a> ';

                        $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                        return $group;
                    })
                    ->editColumn('created_at', function ($data) {
                        return $data->created_at->isoFormat('D MMM Y');
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category->name;
                    })
                    ->addColumn('requester', function ($data) {
                        return $data->userRequester->name;
                    })
                    ->addColumn('office', function ($data) {
                        return $data->userRequester->office->name;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status->name == 'Solved' || $data->status->name == 'Closed') {
                            return '<span style="color:red">' . $data->status->name . '</span>';
                        }
                        return '<span style="color:blue">' . $data->status->name . '</span>';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician != null ? $data->userTechnician->name : "";
                    })
                    ->rawColumns(['action', 'category', 'status', 'technician', 'requester', 'office'])
                    ->make(true);
            }
            return view('ticket.admin.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $office = auth()->user()->office_id;
        $category = Category::get();
        $location = Location::where('office_id', $office)->get();

        return view('ticket.create', compact('category', 'location'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        try {
            //Start menyiapkan data yang diperlukan dan memasukkan ke dalam variable
            $requester = auth()->user()->id;
            $category = Category::where('id', $request->category_id)->first('department_id');
            $technician = User::where('department_id', $category->department_id)->first('id');

            // Start pengecekan apakah user dengan role teknisi pada department ICT atau Engineering sudah ada
            if ($technician == null) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Teknisi belum tersetting, silahkan hubungi Administrator");
            }
            // End pengecekan

            $emailTechnician = User::where('department_id', $category->department_id)->get();

            $emailRequester = auth()->user()->email;

            $paramNo = ParamNumber::where('id', 1)->first();
            $autoNo = $paramNo->ticketNo;
            $department = Category::where('id', $request->category_id)->first();

            $date = Carbon::now('Asia/Singapore');
            // End menyiapkan data

            DB::beginTransaction();

            //Create data to table tickets
            Ticket::create([
                'nomor' => $autoNo,
                'date' => $date,
                'title' => $request->title,
                'category_id' => $request->category_id,
                'department_id' => $department->department_id,
                'location_id' => $request->location_id,
                'status_id' => 1,
                'detail_trouble' => $request->detail_trouble,
                'requester_id' => $requester,
            ]);
            //End Create ticket

            //Create progress on ticket_progress
            TicketProgres::create([
                'ticket_id' => $autoNo,
                'date' => $date,
                'description' => 'Ticket success to created',
                'status_id' => 1,
                'user_id' => auth()->user()->id,
            ]);
            //End Create ticket progress

            //Start update field ticketNo pada table param_numbers
            DB::table('param_numbers')
                ->where('id', 1)
                ->update(['ticketNo' => $autoNo + 1]);
            //End update

            DB::commit();

            //Start send email to requester and technician
            $ticket = Ticket::where('nomor', $autoNo)->first();

            Mail::to($emailRequester)->send(new TicketMail($ticket));

            foreach ($emailTechnician as $key => $email) {
                Mail::to($email->email)->send(new TicketMail($ticket));
            }
            //End send email

            return redirect()->route('ticket.index')
                ->with('success', 'Data success to created');
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $decrypt = Crypt::decryptString($id);
            $ticket = Ticket::find($decrypt);

            $progress = TicketProgres::where('ticket_id', $ticket->id)->get();
            $resolution = TicketProgres::where('ticket_id', $ticket->id)
                ->where('status_id', '!=', 5)->latest()->first();

            //Mencari interval waktu
            $assignDate = new DateTime($ticket->assign_date);
            $solvedDate = new DateTime($ticket->solved_date);
            $interval = $assignDate->diff($solvedDate);
            $year = $interval->y;
            $month = $interval->m;
            $day = $interval->d;
            $hour = $interval->format('%H');
            $minute = $interval->format('%I');
            $second = $interval->format('%S');

            if ($year != 0) {
                $ticketTime = $year . 'y ' . $month . 'm ' .   $day . 'd ' . $hour . ':' . $minute . ':' . $second;
            } elseif ($month != 0) {
                $ticketTime = $month . 'm ' .   $day . 'd ' . $hour . ':' . $minute . ':' . $second;
            } else if ($day != 0) {
                $ticketTime = $day . 'd ' . $hour . ':' . $minute . ':' . $second;
            } else {
                $ticketTime = $hour . ':' . $minute . ':' . $second;
            }
            //End interval waktu

            return view('ticket.show', [
                'ticket'        => $ticket,
                'progress'      => $progress,
                'resolution'    => $resolution,
                'ticketTime'    => $ticketTime
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', "Error on line {$e->getLine()}: {$e->getMessage()}");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $decrypt = Crypt::decryptString($id);
            $ticket = Ticket::find($decrypt);

            $status = Status::where('name', '!=', 'New Request')
                ->where('name', '!=', 'Closed')->get();
            $progress = TicketProgres::where('ticket_id', $ticket->id)->get();
            $resolution = TicketProgres::where('ticket_id', $ticket->id)
                ->where('status_id', '!=', 5)->latest()->first();

            $items = Item::get();

            return view('ticket.technician.update', [
                'ticket'    => $ticket,
                'status'    => $status,
                'progress'  => $progress,
                'items'     => $items,
                'resolution' => $resolution
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketProgressRequest $request, Ticket $ticket)
    {
        DB::beginTransaction();
        try {
            $emailRequester = User::where('id', $ticket->requester_id)->first();
            $date = Carbon::now('Asia/Singapore');

            //Pengecekan jika status update adalah solved maka tambahkan tanggal solved
            if ($request->status_id == 4) {
                $ticket->update([
                    'status_id'     => $request->status_id,
                    'solved_date'   => $date
                ]);
            }

            //Update tbTickets
            $ticket->update([
                'status_id' => $request->status_id,
            ]);

            //Store progress on ticket_progress
            TicketProgres::create([
                'ticket_id' => $ticket->id,
                'date' => $date,
                'description' => $request->description,
                'status_id' => $request->status_id,
                'user_id' => auth()->user()->id,
            ]);

            $ticketUpdate = TicketProgres::where('ticket_id', $ticket->id)->orderBy('id', 'desc')->first();

            //Store item to item_ticket_progress
            if ($request->item_id && $request->keterangan) {
                for ($i = 0; $i < count($request->item_id); $i++) {
                    if ($request->item_id[$i]) {
                        ItemTicket::insert([
                            'item_id'       => $request->item_id[$i],
                            'ticket_id'     => $ticket->id,
                            'description'   => $request->keterangan[$i],
                            'date'          => $date
                        ]);
                    }
                }
            }

            //Send email update ticket progress to requester
            Mail::to($emailRequester->email)->send(new TicketUpdateMail($ticketUpdate));
            //End send email

            DB::commit();
            return redirect()->route('ticket.index')
                ->with('success', 'Progress ticket success to updated');
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
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    /**
     * Assign the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function assign($id)
    {
        DB::beginTransaction();
        try {
            //Mempersiapkan data
            $decrypt = Crypt::decryptString($id);
            $data = Ticket::find($decrypt);
            $technician = auth()->user()->id;
            $emailRequester = User::where('id', $data->requester_id)->first();
            $date = Carbon::now('Asia/Singapore');
            //End

            //Melakukan pengecekan data ticket ada atau tidak
            if (!$data) {
                return response()->json([
                    'status'  => 404,
                    'message' => "Data not found!",
                ], 404);
            }
            //End

            //Update ticket
            $data->update([
                'assign'        => 1,
                'assign_date'   => $date,
                'technician_id' => $technician,
                'status_id'     => 2
            ]);
            //End update ticket

            //Create progress on ticket_progress
            TicketProgres::create([
                'ticket_id' => $data->id,
                'date' => $date,
                'description' => 'Technician has assign your ticket',
                'status_id' => 2,
                'user_id' => $technician,
            ]);
            //End Create ticket progress

            //Send email update ticket progress to requester
            $ticketUpdate = TicketProgres::where('ticket_id', $data->id)->orderBy('id', 'desc')->first();

            Mail::to($emailRequester->email)->send(new TicketUpdateMail($ticketUpdate));
            //End send email

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => "Ticket has been assign",
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 500,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
            ], 500);
        }
    }

    /**
     * Assign the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */

    public function closed($id)
    {
        DB::beginTransaction();
        try {
            //Mempersiapkan data
            $decrypt = Crypt::decryptString($id);
            $data = Ticket::find($decrypt);
            $user = auth()->user();
            $date = Carbon::now('Asia/Singapore');
            //End

            //Melakukan pengecekan data ticket ada atau tidak
            if (!$data) {
                return response()->json([
                    'status'  => 404,
                    'message' => "Data not found!",
                ], 404);
            }
            //End

            //Update ticket
            $data->update([
                'status_id'     => 5
            ]);
            //End update ticket

            //Create progress on ticket_progress
            TicketProgres::create([
                'ticket_id' => $data->id,
                'date' => $date,
                'description' => 'Ticket has been closed by requester',
                'status_id' => 5,
                'user_id' => $user->id,
            ]);
            //End Create ticket progress

            //Send email update ticket progress to requester
            $ticketUpdate = TicketProgres::where('ticket_id', $data->id)->orderBy('id', 'desc')->first();

            Mail::to($user->email)->send(new TicketUpdateMail($ticketUpdate));
            //End send email

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => "Ticket has been closed",
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 500,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
            ], 500);
        }
    }

    /**
     * Display a in progress ticket for technician.
     *
     * @return \Illuminate\Http\Response
     */
    public function progress()
    {
        if (request()->type == 'datatable') {
            $user = auth()->user()->id;
            $data = Ticket::where('technician_id', $user)
                ->where('status_id', '=', 2)
                ->where('assign', '=', 1)->get();

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $showRoute      = 'ticket.show';
                    $updateRoute    = 'ticket.edit';
                    $dataId         = Crypt::encryptString($data->id);

                    $action = "";

                    if ($data->status->name != 'Solved') {
                        $action .= '
                            <a class="btn btn-soft-warning waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($updateRoute, $dataId) . '">
                                Update
                            </a> ';
                    }
                    $action .= '
                        <a class="btn btn-soft-primary waves-effect waves-light me-1" id="btn-show" type="button" href="' . route($showRoute, $dataId) . '">
                            Show
                        </a> ';

                    $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                    return $group;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->isoFormat('D MMM Y');
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('requester', function ($data) {
                    return $data->userRequester->name;
                })
                ->addColumn('office', function ($data) {
                    return $data->userRequester->office->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status->name == 'Solved') {
                        return '<span style="color:red">' . $data->status->name . '</span>';
                    }
                    return '<span style="color:blue">' . $data->status->name . '</span>';
                })
                ->rawColumns(['action', 'category', 'status', 'requester', 'office'])
                ->make(true);
        }

        return view('ticket.technician.index');
    }

    /**
     * Display a hold ticket for technician.
     *
     * @return \Illuminate\Http\Response
     */
    public function hold()
    {
        if (request()->type == 'datatable') {
            $user = auth()->user()->id;
            $data = Ticket::where('technician_id', $user)
                ->where('status_id', '=', 3)
                ->where('assign', '=', 1)->get();

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $showRoute       = 'ticket.show';
                    $dataId          = Crypt::encryptString($data->id);

                    $action = "";
                    $action .= '
                        <a class="btn btn-soft-primary waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($showRoute, $dataId) . '">
                            Show
                        </a> ';

                    $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                    return $group;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->isoFormat('D MMM Y');
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('requester', function ($data) {
                    return $data->userRequester->name;
                })
                ->addColumn('office', function ($data) {
                    return $data->userRequester->office->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status->name == 'Solved') {
                        return '<span style="color:red">' . $data->status->name . '</span>';
                    }
                    return '<span style="color:blue">' . $data->status->name . '</span>';
                })
                ->rawColumns(['action', 'category', 'status', 'requester', 'office'])
                ->make(true);
        }

        return view('ticket.technician.index');
    }

    /**
     * Display a solved ticket for technician.
     *
     * @return \Illuminate\Http\Response
     */
    public function solved()
    {
        if (request()->type == 'datatable') {
            $user = auth()->user()->id;
            $data = Ticket::where('technician_id', $user)
                ->where('assign', '=', 1)
                ->where('solved_date', '!=', null)
                ->orWhere(function ($query) {
                    $query->where('status_id', '=', 4)
                        ->where('status_id', '=', 5);
                })
                ->get();

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $showRoute       = 'ticket.show';
                    $dataId          = Crypt::encryptString($data->id);

                    $action = "";
                    $action .= '
                        <a class="btn btn-soft-primary waves-effect waves-light me-1" id="btn-edit" type="button" href="' . route($showRoute, $dataId) . '">
                            Show
                        </a> ';

                    $group = '<div class="btn-group btn-group-sm mb-1 mb-md-0" role="group">
                            ' . $action . '
                        </div>';
                    return $group;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->isoFormat('D MMM Y');
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('requester', function ($data) {
                    return $data->userRequester->name;
                })
                ->addColumn('office', function ($data) {
                    return $data->userRequester->office->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status->name == 'Solved' || $data->status->name == 'Closed') {
                        return '<span style="color:red">' . $data->status->name . '</span>';
                    }
                    return '<span style="color:blue">' . $data->status->name . '</span>';
                })
                ->rawColumns(['action', 'category', 'status', 'requester', 'office'])
                ->make(true);
        }

        return view('ticket.technician.index');
    }
}
