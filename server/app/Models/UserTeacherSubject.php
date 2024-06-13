<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeacherSubject extends Model
{
    use HasFactory;

    protected $fillable = ['userTeacherId', 'subjectId'];

    public function teacher()
    {
        return $this->belongsTo(UserTeacher::class, 'userTeacherId');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subjectId');
    }

    public function groupSubjects()
    {
        return $this->hasMany(GroupSubject::class, 'teacherSubjectId');
    }
}
