<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 'date', 'title', 'category_id', 'department_id',
        'location_id', 'office_id', 'status', 'detail_trouble',
        'requester_id', 'technician_id', 'assign', 'assign_date',
        'solved_date'
    ];

    protected $with = ['category', 'location'];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    // public function status()
    // {
    //     return $this->belongsTo(Status::class);
    // }

    public function userRequester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function userTechnician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketProgress()
    {
        return $this->hasMany(TicketProgres::class, 'ticket_id', 'id')
            ->where('status', '=', 'Solved');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function item()
    {
        return $this->belongsToMany(
            Item::class,
            'item_ticket',
            'ticket_id',
            'item_id'
        )
            ->withPivot(['description', 'date']);
    }
}
