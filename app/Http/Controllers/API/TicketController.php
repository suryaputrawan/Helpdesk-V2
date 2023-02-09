<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\ParamNumber;
use Illuminate\Http\Request;
use App\Models\TicketProgres;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\TicketResource;
use App\Http\Requests\TicketProgressRequest;
use App\Http\Resources\TicketProgressResource;
use App\Http\Resources\TicketSingleResource;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Success',
            'data' => TicketResource::collection(Ticket::paginate(15)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        ///Menyiapkan data yang diperlukan dan memasukkan ke dalam variable
        $requester = auth()->user()->id;

        $category = Category::where('id', $request->category_id)->first('department_id');
        $technician = User::where('department_id', $category->department_id)->first('id');

        $emailTechnician = User::where('department_id', $category->department_id)->first();
        $emailRequester = auth()->user()->email;

        $paramNo = ParamNumber::where('id', 1)->first();
        $autoNo = $paramNo->ticketNo;

        $date = Date::now();

        //Create data to table tickets
        $ticket = Ticket::create([
            'nomor' => $autoNo,
            'date' => $date,
            'title' => $request->title,
            'category_id' => $request->category_id,
            'priority' => 1,
            'status_id' => 1,
            'detail_trouble' => $request->detail_trouble,
            'requester_id' => $requester,
            'technician_id' => $technician->id,
        ]);

        //Create progress on ticket_progress
        TicketProgres::create([
            'ticket_id' => $autoNo,
            'date' => $date,
            'description' => 'Ticket success to created',
            'status_id' => 1,
            'user_id' => auth()->user()->id,
        ]);

        //Update field ticketNo pada table param_numbers
        DB::table('param_numbers')
            ->where('id', 1)
            ->update(['ticketNo' => $autoNo + 1]);

        return response()->json([
            'message' => 'Ticket Success to Created',
            'data' => new TicketSingleResource($ticket),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return response()->json([
            'message' => 'Success',
            'data' => new TicketSingleResource($ticket),
        ]);
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
        $emailRequester = User::where('id', $ticket->requester_id)->first();
        $date = Date::now();
        $user = auth()->user()->id;

        //Update tbTickets
        $ticket->update([
            'status_id' => $request->status_id,
        ]);

        //Create progress on ticket_progress
        $ticketProgress = TicketProgres::create([
            'ticket_id' => $ticket->id,
            'date' => $date,
            'description' => $request->description,
            'status_id' => $request->status_id,
            'user_id' => $user,
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => new TicketProgressResource($ticketProgress),
        ]);
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
}
