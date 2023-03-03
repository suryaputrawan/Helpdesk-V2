<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Office;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == "Technician") {
            if (request()->type == 'datatable') {
                $technician = auth()->user()->id;

                $data = Ticket::where('technician_id', '=', $technician)
                    ->with([
                        'userRequester' => function ($query) {
                            $query->select('id', 'name', 'office_id');
                        },
                        'userTechnician' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'office' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    //Filter data berdasarkan 2 tanggal
                    ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                        return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                    })
                    //Filter data tanpa relasi
                    ->when(request('office_id') != null && request('office_id') != 'NaN', function ($query) {;
                        return $query->where('office_id', '=', request('office_id'));
                    })
                    ->when(request('category_id') != null && request('category_id') != 'NaN', function ($query) {
                        return $query->where('category_id', '=', request('category_id'));
                    })
                    ->when(request('status_filter') != null && request('status_filter') != 'NaN', function ($query) {
                        return $query->where('status', '=', request('status_filter'));
                    })
                    ->select(
                        'id',
                        'nomor',
                        'date',
                        'title',
                        'category_id',
                        'location_id',
                        'office_id',
                        'status',
                        'requester_id',
                        'technician_id',
                        'assign_date',
                        'solved_date'
                    )->get();

                return datatables()->of($data)
                    ->editColumn('date', function ($data) {
                        $date = new DateTime($data->date);
                        return $data->date ? $date->format('d M Y') : '';
                    })
                    ->editColumn('assign_date', function ($data) {
                        $date = new DateTime($data->assign_date);
                        return $data->assign_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->editColumn('solved_date', function ($data) {
                        $date = new DateTime($data->solved_date);
                        return $data->solved_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category ? $data->category->name : '';
                    })
                    ->addColumn('location', function ($data) {
                        return $data->location ? $data->location->name : '';
                    })
                    ->addColumn('status', function ($data) {
                        return $data->status ? $data->status : '';
                    })
                    ->addColumn('requester', function ($data) {
                        return $data->userRequester ? $data->userRequester->name : '';
                    })
                    ->addColumn('office', function ($data) {
                        return $data->office->name;
                    })
                    ->addColumn('interval', function ($data) {
                        //Mencari interval waktu
                        $assignDate = new DateTime($data->assign_date);
                        $solvedDate = new DateTime($data->solved_date);
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

                        return $data->solved_date ? '<span style="color:red">' . $ticketTime . '</span>' : '';
                    })
                    ->rawColumns(['interval'])
                    ->make(true);
            }
            return view('report.technician.index', [
                'office'        => Office::get(['id', 'name']),
                'category'      => Category::where('department_id', '=', auth()->user()->department_id)->get()
            ]);
        } elseif (auth()->user()->role == "Management") {
            if (request()->type == 'datatable') {
                $data = Ticket::where('office_id', auth()->user()->office_id)->latest()
                    ->with([
                        'userRequester' => function ($query) {
                            $query->select('id', 'name', 'office_id');
                        },
                        'userTechnician' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'office' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    //Filter data berdasarkan 2 tanggal
                    ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                        return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                    })
                    //Filter data tanpa relasi
                    ->when(request('office_id') != null && request('office_id') != 'NaN', function ($query) {
                        return $query->where('office_id', '=', request('office_id'));
                    })
                    ->when(request('category_id') != null && request('category_id') != 'NaN', function ($query) {
                        return $query->where('category_id', '=', request('category_id'));
                    })
                    ->when(request('technician_id') != null && request('technician_id') != 'NaN', function ($query) {
                        return $query->where('technician_id', '=', request('technician_id'));
                    })
                    ->select(
                        'id',
                        'nomor',
                        'date',
                        'title',
                        'category_id',
                        'location_id',
                        'office_id',
                        'status',
                        'requester_id',
                        'technician_id',
                        'assign_date',
                        'solved_date'
                    )->get();

                return datatables()->of($data)
                    ->editColumn('date', function ($data) {
                        $date = new DateTime($data->date);
                        return $data->date ? $date->format('d M Y') : '';
                    })
                    ->editColumn('assign_date', function ($data) {
                        $date = new DateTime($data->assign_date);
                        return $data->assign_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->editColumn('solved_date', function ($data) {
                        $date = new DateTime($data->solved_date);
                        return $data->solved_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category ? $data->category->name : '';
                    })
                    ->addColumn('location', function ($data) {
                        return $data->location ? $data->location->name : '';
                    })
                    ->addColumn('status', function ($data) {
                        return $data->status ? $data->status : '';
                    })
                    ->addColumn('requester', function ($data) {
                        return $data->userRequester ? $data->userRequester->name : '';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician ? $data->userTechnician->name : '';
                    })
                    ->addColumn('office', function ($data) {
                        return $data->office->name;
                    })
                    ->addColumn('interval', function ($data) {
                        //Mencari interval waktu
                        $assignDate = new DateTime($data->assign_date);
                        $solvedDate = new DateTime($data->solved_date);
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

                        return $data->solved_date ? '<span style="color:red">' . $ticketTime . '</span>' : '';
                    })
                    ->addColumn('resolution', function ($data) {
                        if ($data->ticketProgress != Null) {
                            foreach ($data->ticketProgress as $progress) {
                                return $progress->description;
                            }
                        } else {
                            return '';
                        }
                    })
                    ->rawColumns(['interval', 'resolution'])
                    ->make(true);
            }
            return view('report.index', [
                'office'        => Office::where('id', auth()->user()->office_id)->get(['id', 'name']),
                'technician'    => User::where('role', 'Technician')->get(),
                'category'      => Category::get(['id', 'name'])
            ]);
        } else {
            if (request()->type == 'datatable') {
                $data = Ticket::latest()
                    ->with([
                        'userRequester' => function ($query) {
                            $query->select('id', 'name', 'office_id');
                        },
                        'userTechnician' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'office' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    //Filter data berdasarkan 2 tanggal
                    ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                        return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                    })
                    //Filter data tanpa relasi
                    ->when(request('office_id') != null && request('office_id') != 'NaN', function ($query) {
                        return $query->where('office_id', '=', request('office_id'));
                    })
                    ->when(request('category_id') != null && request('category_id') != 'NaN', function ($query) {
                        return $query->where('category_id', '=', request('category_id'));
                    })
                    ->when(request('technician_id') != null && request('technician_id') != 'NaN', function ($query) {
                        return $query->where('technician_id', '=', request('technician_id'));
                    })
                    ->select(
                        'id',
                        'nomor',
                        'date',
                        'title',
                        'category_id',
                        'location_id',
                        'office_id',
                        'status',
                        'requester_id',
                        'technician_id',
                        'assign_date',
                        'solved_date'
                    )->get();

                return datatables()->of($data)
                    ->editColumn('date', function ($data) {
                        $date = new DateTime($data->date);
                        return $data->date ? $date->format('d M Y') : '';
                    })
                    ->editColumn('assign_date', function ($data) {
                        $date = new DateTime($data->assign_date);
                        return $data->assign_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->editColumn('solved_date', function ($data) {
                        $date = new DateTime($data->solved_date);
                        return $data->solved_date ? $date->format('d M Y H:i:s') : '';
                    })
                    ->addColumn('category', function ($data) {
                        return $data->category ? $data->category->name : '';
                    })
                    ->addColumn('location', function ($data) {
                        return $data->location ? $data->location->name : '';
                    })
                    ->addColumn('status', function ($data) {
                        return $data->status ? $data->status : '';
                    })
                    ->addColumn('requester', function ($data) {
                        return $data->userRequester ? $data->userRequester->name : '';
                    })
                    ->addColumn('technician', function ($data) {
                        return $data->userTechnician ? $data->userTechnician->name : '';
                    })
                    ->addColumn('office', function ($data) {
                        return $data->office->name;
                    })
                    ->addColumn('interval', function ($data) {
                        //Mencari interval waktu
                        $assignDate = new DateTime($data->assign_date);
                        $solvedDate = new DateTime($data->solved_date);
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

                        return $data->solved_date ? '<span style="color:red">' . $ticketTime . '</span>' : '';
                    })
                    ->addColumn('resolution', function ($data) {
                        if ($data->ticketProgress != Null) {
                            foreach ($data->ticketProgress as $progress) {
                                return $progress->description;
                            }
                        } else {
                            return '';
                        }
                    })
                    ->rawColumns(['interval', 'resolution'])
                    ->make(true);
            }
            return view('report.index', [
                'office'        => Office::get(['id', 'name']),
                'technician'    => User::where('role', 'Technician')->get(),
                'category'      => Category::get(['id', 'name'])
            ]);
        }
    }

    public function exportPdf()
    {
        if (auth()->user()->role == "Technician") {
            $technician = auth()->user()->id;
            $tickets = Ticket::where('technician_id', $technician)
                ->with([
                    'userRequester' => function ($query) {
                        $query->select('id', 'name', 'office_id');
                    },
                    'userTechnician' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'ticketProgress' => function ($query) {
                        $query->select('id', 'ticket_id', 'description');
                    },
                    'office' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                //Filter data berdasarkan 2 tanggal
                ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                    return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                })
                //Filter data berelasi
                ->when(request('office') != null && request('office') != 'NaN', function ($query) {
                    return $query->where('office_id', '=', request('office'));
                })
                //Filter data tanpa relasi
                ->when(request('category') != null && request('category') != 'NaN', function ($query) {
                    return $query->where('category_id', '=', request('category'));
                })
                ->when(request('status') != null && request('status') != 'NaN', function ($query) {
                    return $query->where('status', '=', request('status'));
                })
                ->select(
                    'id',
                    'nomor',
                    'date',
                    'title',
                    'category_id',
                    'location_id',
                    'office_id',
                    'status',
                    'requester_id',
                    'technician_id',
                    'assign_date',
                    'solved_date'
                )->get();
        } elseif (auth()->user()->role == "Management") {
            $tickets = Ticket::where('office_id', auth()->user()->office_id)->latest()
                ->with([
                    'userRequester' => function ($query) {
                        $query->select('id', 'name', 'office_id');
                    },
                    'userTechnician' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'ticketProgress' => function ($query) {
                        $query->select('id', 'ticket_id', 'description');
                    },
                    'office' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                //Filter data berdasarkan 2 tanggal
                ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                    return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                })
                //Filter data berelasi
                ->when(request('office') != null && request('office') != 'NaN', function ($query) {
                    return $query->where('office_id', '=', request('office'));
                })
                //Filter data tanpa relasi
                ->when(request('category') != null && request('category') != 'NaN', function ($query) {
                    return $query->where('category_id', '=', request('category'));
                })
                ->when(request('technician_id') != null && request('technician_id') != 'NaN', function ($query) {
                    return $query->where('technician_id', '=', request('technician'));
                })
                ->select(
                    'id',
                    'nomor',
                    'date',
                    'title',
                    'category_id',
                    'location_id',
                    'office_id',
                    'status',
                    'requester_id',
                    'technician_id',
                    'assign_date',
                    'solved_date'
                )->get();
        } else {
            $tickets = Ticket::latest()
                ->with([
                    'userRequester' => function ($query) {
                        $query->select('id', 'name', 'office_id');
                    },
                    'userTechnician' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'ticketProgress' => function ($query) {
                        $query->select('id', 'ticket_id', 'description');
                    },
                    'office' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                //Filter data berdasarkan 2 tanggal
                ->when(request('start_date') != null && request('end_date') != null, function ($query) {
                    return  $query->whereBetween(DB::raw('DATE(date)'), [request('start_date'), request('end_date')]);
                })
                //Filter data berelasi
                ->when(request('office') != null && request('office') != 'NaN', function ($query) {
                    return $query->where('office_id', '=', request('office'));
                })
                //Filter data tanpa relasi
                ->when(request('category') != null && request('category') != 'NaN', function ($query) {
                    return $query->where('category_id', '=', request('category'));
                })
                ->when(request('technician') != null && request('technician') != 'NaN', function ($query) {
                    return $query->where('technician_id', '=', request('technician'));
                })
                ->select(
                    'id',
                    'nomor',
                    'date',
                    'title',
                    'category_id',
                    'location_id',
                    'office_id',
                    'status',
                    'requester_id',
                    'technician_id',
                    'assign_date',
                    'solved_date'
                )->get();
        }
        $pdf = PDF::loadview('report/export/ticketPdf', compact('tickets'))->setPaper('Legal', 'landscape');
        return $pdf->stream();
    }
}
