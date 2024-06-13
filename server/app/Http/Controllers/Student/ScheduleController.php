<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Group as GroupModal;
use App\Models\GroupScheduleClass;

class ScheduleController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user;
        $groupId = $user['groupId'];
        $subgroup = $user['subgroup'];

        $validated = $request->validate([
            'dayWeek' => 'integer|min:1|max:6',
        ]);

        $group = GroupModal::find($groupId);

        if (!$group) {
            return response()->json(['error' => '404 Group Not Found', 'status'=>404], 404);
        }

        $dates = [];

        // Получаем расписание для указанного дня недели
        if (isset($validated['dayWeek'])) {
            $scheduleClasses = GroupScheduleClass::where('groupId', $groupId)
                ->where('dayWeek', $validated['dayWeek'])
                ->get();
        } else {
            $scheduleClasses = GroupScheduleClass::where('groupId', $groupId)
                ->get();
            
            foreach ([1,2,3,4,5,6,7] as $dayWeek) {
                $dates[] = GroupScheduleClass::with(['subject.teacherSubject.teacher.auditorium'])->where('groupId', $groupId)->where('dayWeek', $dayWeek)->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'groupId' => $item->groupId,
                        'subjectId' => $item->subjectId,
                        'subgroup' => $item->subgroup,
                        'number' => $item->number,
                        'dayWeek' => $item->dayWeek,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'auditorium' => $item->subject->teacherSubject->teacher->auditorium->number ?? null,
                    ];
                })->filter(function($item) use ($subgroup) {
                    return $item->subgroup == null ||  $item->subgroup == $subgroup; 
                });
            }
        }


        return response()->json([
            'message' => 'Schedule with replacements retrieved successfully',
            'data' => $dates
        ], 200);
    }
}
