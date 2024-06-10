<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Group as GroupModal;
use App\Models\GroupSubject as GroupSubject;

class TeacherController extends Controller
{
    public function teacherSubjectList(Request $request, $groupId) {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $group = GroupModal::find($groupId);
        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }
        
        $groupSubjects = GroupSubject::with(['teacherSubject', 'teacherSubject.teacher', 'group'])
            ->whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('userTeacherId', $teacherId);
            })
            ->where('groupId', $groupId)
            ->get();

        return response()->json(['status' => 200, 'data' => $groupSubjects], 200);
    }
}
