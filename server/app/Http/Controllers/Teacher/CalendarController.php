<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            'date' => 'required|date',
        ]);

        $teacher = $request->user;
        $teacherId = $teacher['id'];

        $date = $validated['date'];
        
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

    public function eventsByDateRange(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $teacher = $request->user();
        $teacherId = $teacher['id'];

        $groups = DB::table('groups')
            ->distinct()
            ->join('group_subjects', 'groups.id', '=', 'group_subjects.groupId')
            ->join('user_teacher_subjects', 'group_subjects.teacherSubjectId', '=', 'user_teacher_subjects.id')
            ->where('user_teacher_subjects.userTeacherId', $teacherId)
            ->select('groups.id')
            ->get();

        $groupIds = $groups->pluck('id');

        $events = CalendarEvent::whereIn('groupId', $groupIds)
            ->whereBetween('date', [$startDate, $endDate])
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