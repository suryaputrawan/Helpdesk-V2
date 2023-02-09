<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTicket extends Model
{
    use HasFactory;

    protected $table = 'item_ticket';

    protected $fillable = ['item_id', 'ticket_id', 'description', 'date'];
}
