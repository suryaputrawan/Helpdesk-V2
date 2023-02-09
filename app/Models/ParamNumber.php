<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParamNumber extends Model
{
    use HasFactory;

    protected $fillable = ['ticketNo'];
}
