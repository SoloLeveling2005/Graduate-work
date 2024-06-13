<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function me(Request $request) {
        $teacherId = ($request->user)['id'];

        $teacher = UserTeacher::with(['auditorium', 'groups', 'teacherSubject', 'scheduleClasses'])->find($teacherId);

        return response()->json($teacher, 200);
    }
}
