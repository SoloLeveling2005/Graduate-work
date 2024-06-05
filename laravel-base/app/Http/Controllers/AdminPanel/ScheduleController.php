<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GroupSchedule;
use App\Models\GroupScheduleClass;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Получение расписания группы.
     */
    public function list(Request $request, $groupId)
    {
        $schedule = GroupSchedule::where('groupId', $groupId)->with(['classes.teacher', 'classes'])->get();

        return response()->json($schedule);
    }

    /**
     * Изменение предмета в расписании за определенный день.
     */
    public function changeSubject(Request $request, $scheduleClassId)
    {
        $request->validate([
            'subjectId' => 'required|exists:subjects,id',
            'userTeacherId' => 'required|exists:user_teachers,id',
            'subgroup' => 'nullable|string|in:A,B',
        ]);

        $scheduleClass = GroupScheduleClass::findOrFail($scheduleClassId);
        $scheduleClass->userTeacherId = $request->input('userTeacherId');
        $scheduleClass->subgroup = $request->input('subgroup');
        $scheduleClass->save();

        return response()->json(['success' => true, 'scheduleClass' => $scheduleClass]);
    }
}
