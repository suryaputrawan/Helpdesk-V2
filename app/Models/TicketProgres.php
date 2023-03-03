<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketProgres extends Model
{
    use HasFactory;

    protected $table = 'ticket_progress';

    protected $fillable = ['ticket_id', 'date', 'description', 'status', 'user_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // public function status()
    // {
    //     return $this->belongsTo(Status::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
