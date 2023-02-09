<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function ticket()
    {
        return $this->belongsToMany(
            Ticket::class,
            'item_ticket',
            'item_id',
            'ticket_id'
        );
    }
}
