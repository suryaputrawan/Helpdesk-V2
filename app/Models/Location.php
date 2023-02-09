<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'office_id'];

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
