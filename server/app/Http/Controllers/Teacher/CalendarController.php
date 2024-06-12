<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function listGroups()
    {
        $groups = Group::all();
        return response()->json($groups);
    }

    public function eventsByDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
        ]);

        $teacherId = $request->teacher['id'];

        $date = $validated['date'] ?? today()->toDateString();

        $groups = DB::table('groups')
            ->distinct()
            ->join('group_subjects', 'groups.id', '=', 'group_subjects.groupId')
            ->join('user_teacher_subjects', 'group_subjects.teacherSubjectId', '=', 'user_teacher_subjects.id')
            ->where('user_teacher_subjects.userTeacherId', $teacherId)
            ->select('groups.id')
            ->get();

        $groupIds = $groups->pluck('id');

        $events = CalendarEvent::whereIn('groupId', $groupIds)
            ->whereDate('date', $date)
            ->get();

        return response()->json($events);
    }


    public function createEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'place' => 'nullable|string|max:255',
            'eventType' => 'required|boolean',
            'groupId' => 'required|exists:groups,id',
            'subgroup' => 'nullable|string|in:A,B',
        ]);

        $event = CalendarEvent::create($validated);
        return response()->json($event, 201);
    }

}