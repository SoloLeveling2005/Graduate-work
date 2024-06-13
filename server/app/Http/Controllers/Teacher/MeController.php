<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTeacher;

class MeController extends Controller
{
    public function me(Request $request) {
        $teacherId = ($request->user)['id'];

        $teacher = UserTeacher::with(['auditorium', 'groups', 'teacherSubject', 'teacherSubject.teacher', 'teacherSubject.subject', 'teacherSubject.groupSubjects'])->find($teacherId);

        return response()->json($teacher, 200);
    }
}
