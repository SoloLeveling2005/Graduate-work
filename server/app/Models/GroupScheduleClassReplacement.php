<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupScheduleClassReplacement extends Model
{
    use HasFactory;

    protected $table = 'group_schedule_class_replacement_rq';

    protected $fillable = [
        'groupId',
        'date',
        'subjectId',
        'subgroup',
        'number',
        'reason'
    ];

    public function subject()
    {
        return $this->belongsTo(GroupSubject::class, 'subjectId');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupId');
    }
}
