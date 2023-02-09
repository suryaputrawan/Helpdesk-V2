<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketProgres;
use App\Mail\TicketUpdateMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyAssignTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to assign ticket for technician';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tickets = Ticket::where('assign', 0)->get();
        $date = Carbon::now('Asia/Singapore');

        foreach ($tickets as $key => $ticket) {
            if ($tickets[$key] != null) {
                $technician = User::where('department_id', $ticket->department_id)->first();
                $requester = User::where('id', $ticket->requester_id)->first();

                $ticket->update([
                    'assign'        => 1,
                    'assign_date'   => $date,
                    'technician_id' => $technician->id,
                    'status_id'     => 2
                ]);

                TicketProgres::create([
                    'ticket_id'     => $ticket->id,
                    'date'          => $date,
                    'description'   => 'System has assign ticket for technician',
                    'status_id'     => 2,
                    'user_id'       => 1
                ]);

                //Send email update ticket progress to requester
                $ticketUpdate = TicketProgres::where('ticket_id', $ticket->id)->orderBy('id', 'desc')->first();

                Mail::to($requester->email)->send(new TicketUpdateMail($ticketUpdate));
                //End send email
            }
        }
    }
}
