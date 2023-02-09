<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;

        $ticketRequester = Ticket::where('requester_id', $user)->get();

        $ticketTechnician = Ticket::where('technician_id', $user)->get();

        return view('dashboard.index');
    }
}
