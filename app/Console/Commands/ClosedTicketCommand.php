<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketProgres;
use App\Mail\TicketUpdateMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ClosedTicketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change status ticket from solved to closed';

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
        $tickets = Ticket::where('status_id', 4)
            ->where('assign', 1)->get();
        $date = Carbon::now('Asia/Singapore');

        foreach ($tickets as $key => $data) {
            if ($tickets[$key] != null) {
                $requester = User::where('id', $data->requester_id)->first();

                $data->update([
                    'status_id' => 5
                ]);

                TicketProgres::create([
                    'ticket_id'     => $data->id,
                    'date'          => $date,
                    'description'   => 'System has been closed your ticket',
                    'status_id'     => 5,
                    'user_id'       => 1
                ]);

                //Send email update ticket progress to requester
                $ticketUpdate = TicketProgres::where('ticket_id', $data->id)->orderBy('id', 'desc')->first();

                Mail::to($requester->email)->send(new TicketUpdateMail($ticketUpdate));
                //End send email
            }
        }
    }
}
