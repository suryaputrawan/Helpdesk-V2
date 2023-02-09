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
}
