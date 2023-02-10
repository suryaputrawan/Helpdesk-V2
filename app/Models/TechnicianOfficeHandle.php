<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianOfficeHandle extends Model
{
    use HasFactory;

    protected $table = 'technician_office_handles';

    protected $fillable = ['user_id', 'office_id'];

    // public function users()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // public function offices()
    // {
    //     return $this->belongsTo(Office::class, 'office_id');
    // }
}
