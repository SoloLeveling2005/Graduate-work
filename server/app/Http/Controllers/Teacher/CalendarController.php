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

    public function eventsToday(Request $request)
    {
        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $groups = DB::table('groups')
            ->distinct()
            ->join('group_subjects', 'groups.id', '=', 'group_subjects.groupId')
            ->join('user_teacher_subjects', 'group_subjects.teacherSubjectId', '=', 'user_teacher_subjects.id')
            ->where('user_teacher_subjects.userTeacherId', $teacherId)
            ->select('groups.*')
            ->get('id');

        // $events = CalendarEvent::where('groupId', $groupId)
        //     ->whereDate('date', today())
        //     ->get();
        return response()->json($groups->toArray()->map(function($group) {
            return $group['id'];
        }));
    }

    public function eventsByDate(Request $request)
    {
        $validated = $request->validate([
            'groupId' => 'required|integer',
            'date' => 'required|date',
        ]);

        $groupId = $validated['groupId'];
        $date = $validated['date'];

        $events = CalendarEvent::where('groupId', $groupId)
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