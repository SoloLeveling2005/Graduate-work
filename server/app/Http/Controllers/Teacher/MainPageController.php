<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserTeacher;
use App\Models\GroupScheduleClass;

class MainPageController extends Controller
{
    public function getCurrentClass($time) {
        $schedule = [
            '1' => ['08:30', '10:00'],
            '1.5' => ['10:00', '10:10'],
            '2' => ['10:10', '11:40'],
            '2.5' => ['11:40', '12:10'],
            '3' => ['12:10', '13:40'],
            '3.5' => ['13:40', '13:50'],
            '4' => ['13:50', '15:20'],
            '4.5' => ['15:20', '15:40'],
            '5' => ['15:40', '17:10'],
            '5.5' => ['17:10', '17:20'],
            '6' => ['17:20', '18:50'],
        ];

        foreach ($schedule as $class => $timeRange) {
            $startTime = Carbon::createFromTimeString($timeRange[0], 'Asia/Almaty');
            $endTime = Carbon::createFromTimeString($timeRange[1], 'Asia/Almaty');
            if ($time->between($startTime, $endTime)) {
                return $class;
            }
        }

        return 'Отдых';
    }

    public function getTodayShedule(Request $request) {
        $teacherId = ($request->user)['id'];

        // Получение текущей даты и времени в Астане
        $currentDateTime = Carbon::now('Asia/Almaty');

        // Получение текущего дня недели (от 1 до 7)
        $dayOfWeek = $currentDateTime->dayOfWeekIso;

        $schedule = GroupScheduleClass::with(['subject.teacherSubject.subject', 'subject.teacherSubject.teacher.auditorium'])
            ->where('dayWeek', $dayOfWeek)
            ->get()
            ->filter(function($item) use ($teacherId) {
                return $item->subject->teacherSubject->userTeacherId == $teacherId;
            })
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'groupId' => $item->groupId,
                    'subgroup' => $item->subgroup,
                    'number' => $item->number,
                    'dayWeek' => $item->dayWeek,
                    'subjectTitle' => $item->subject->teacherSubject->subject->title,
                    'auditorium' => $item->subject->teacherSubject->teacher->auditorium->number
                ];
            })
            ->sortBy('number')
            ->values();

        return response()->json($schedule, 200);
    }
}
