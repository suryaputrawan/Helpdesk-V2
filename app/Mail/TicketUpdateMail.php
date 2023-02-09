<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketUpdate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketUpdate)
    {
        $this->ticketUpdate = $ticketUpdate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Update Request ID: ## ' . $this->ticketUpdate->ticket_id . ' ##] with status : ' . $this->ticketUpdate->status->name)
            ->view('ticket.emailUpdate');
    }
}
