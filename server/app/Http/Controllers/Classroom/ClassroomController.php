<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom as ClassroomModel;

class ClassroomController extends Controller
{
    public function indexForTeachers(Request $request)
    {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $classrooms = ClassroomModel::with(['groupSubject.teacherSubject.teacher','groupSubject.group', 'groupSubject.teacherSubject.subject'])->whereHas('groupSubject.teacherSubject.teacher', function($query) use ($teacherId) {
            $query->where('id', $teacherId);
        })->get();
        return response()->json($classrooms);
    }

    public function indexForStudents(Request $request)
    {
        $student = $request->user;
        $studentId = $student['id'];

        $classrooms = ClassroomModel::whereHas('groupSubject.group.students', function($query) {
            $query->where('student_id', $studentId);
        })->get();
        return response()->json($classrooms);
    }
}
