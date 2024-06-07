<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\GroupScheduleClass;

class ScheduleController extends Controller
{

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
        $scheduleClasses = GroupScheduleClass::whereHas('subject.teacherSubject', function ($query) use ($teacherId) {
            $query->where('userTeacherId', $teacherId);
        })
        ->whereBetween('date', [$startDate, $endDate])
        ->with(['group', 'subject.teacherSubject.subject'])
        ->get();

        // Форматирование расписания в нужный формат
        $schedule = [];
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dailySchedule = $scheduleClasses->filter(function ($class) use ($date) {
                return $class->date->format('Y-m-d') == $date->format('Y-m-d');
            });

            $formattedDailySchedule = $dailySchedule->map(function ($class) {
                return [
                    'subject' => $class->subject->teacherSubject->subject->name,
                    'group' => $class->group->title,
                    'subgroup' => $class->subgroup,
                    'number' => $class->number,
                ];
            });

            $schedule[] = [
                'day_of_week' => $date->format('l'),
                'date' => $date->format('Y-m-d'),
                'classes' => $formattedDailySchedule->values()->all(),
            ];
        }

        return response()->json($schedule);
    }


}