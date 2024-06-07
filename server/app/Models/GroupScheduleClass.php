<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScheduleClass extends Model
{
    use HasFactory;

    protected $fillable = ['groupId', 'number', 'date', 'subgroup','subjectId'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }
    
    public function subject()
    {
        return $this->belongsTo(GroupSubject::class, 'subjectId');
    }

    public function replacementRequests()
    {
        return $this->hasMany(GroupScheduleClassReplacementRequest::class, 'groupScheduleClassId');
    }

    public function replacements()
    {
        return $this->hasMany(GroupScheduleClassReplacement::class, 'groupScheduleClassId');
    }

    
}
