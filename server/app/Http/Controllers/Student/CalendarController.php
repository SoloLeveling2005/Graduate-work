<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Group;
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
            'date' => 'required|date',
        ]);

        $date = $validated['date'];

        $user = $request->user();
        dd($user);

        $events = CalendarEvent::where('groupId', $groupId)
            ->whereDate('date', today())
            ->get();
        return response()->json($events);
    }
}