<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function location()
    {
        return $this->hasMany(Location::class);
    }

    // public function technicianOfficeHandles()
    // {
    //     return $this->hasMany(TechnicianOfficeHandle::class, 'office_id', 'id');
    // }

    public function officeHandles()
    {
        return $this->belongsToMany(
            User::class,
            'technician_office_handles',
            'office_id',
            'user_id'
        );
    }
}
