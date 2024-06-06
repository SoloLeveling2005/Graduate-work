<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getWeeklySchedule(Request $request)
    {
        $teacher = $request->user();
        $today = now();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $schedule = $teacher->schedule()->whereBetween('date', [$startOfWeek, $endOfWeek])->get();

        return response()->json($schedule);
    }

    
}
