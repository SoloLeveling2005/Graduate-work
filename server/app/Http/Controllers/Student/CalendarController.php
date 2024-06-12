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

    public function eventsToday($groupId)
    {
        $events = CalendarEvent::where('groupId', $groupId)
            ->whereDate('date', today())
            ->get();
        return response()->json($events);
    }

    public function eventsByDate($groupId, $date)
    {
        $events = CalendarEvent::where('groupId', $groupId)
            ->whereDate('date', $date)
            ->get();
        return response()->json($events);
    }
}