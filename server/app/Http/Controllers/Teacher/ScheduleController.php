<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\GroupScheduleClass;

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


    public function list(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d'
        ]);

        $teacher = $request->user;

        // Получение входных данных или установка значений по умолчанию
        $startDate = $request->input('start_date') ?: Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = $request->input('end_date') ?: Carbon::now()->addMonth()->format('Y-m-d');
        $teacherId = $teacher['id'];

        // Получение расписания преподавателя за указанный период
        $schedule = GroupScheduleClass::whereHas('subject.teacherSubject', function ($query) use ($teacherId) {
            $query->where('userTeacherId', $teacherId);
        })
        ->whereBetween('date', [$startDate, $endDate])
        ->with(['group', 'subject.teacherSubject.teacher'])
        ->get();

        return response()->json($schedule);
    }

}