<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_id'];
    protected $with = ['department'];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }
}
