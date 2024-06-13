<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Group;
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

        $date = $validated['date'];

        $user = $request->user;
        $groupId = $user['groupId'];

        $events = CalendarEvent::where('groupId', $groupId)
            ->whereDate('date', $date)
            ->get();
        return response()->json($events);
    }

    public function allEvents(Request $request) {
        $user = $request->user;
        $groupId = $user['groupId'];

        $events = CalendarEvent::where('groupId', $groupId)->get();
        return response()->json($events);
    }

    public function eventsByDateRange(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $student = $request->user;
        $studentId = $student['id'];
        $groupId = $student['groupId'];

        $events = CalendarEvent::where('groupId', $groupId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($events);
    }
}