<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groupSubject()
    {
        return $this->hasMany(GroupSubject::class, 'id', 'groupSubjectId');
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
