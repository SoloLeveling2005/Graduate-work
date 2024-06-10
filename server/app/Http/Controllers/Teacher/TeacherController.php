<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function teacherSubjectList(Request $request, $groupId) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $group = GroupModal::find($groupId);
        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }
        
        $groupSubjects = DB::table('group_subjects')
            ->join('user_teacher_subjects', 'group_subjects.teacherSubjectId', '=', 'user_teacher_subjects.id')
            ->where('user_teacher_subjects.userTeacherId', $teacherId)
            ->where('group_subjects.groupId', $groupId)
            ->select('group_subjects.*')
            ->get();

        return response()->json(['status' => 200, 'data' => $groupSubjects], 200);
    }
}
