<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'date', 
        'time', 
        'place', 
        'eventType', 
        'groupId', 
        'subgroup'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }
}
