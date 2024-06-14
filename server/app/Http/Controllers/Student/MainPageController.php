<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\UserStudent;

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

    public function getCurrentLessonTestVersion(Request $request) {
        return response()->json([
            "id"=> 8,
            "groupId"=> 1,
            "subjectId"=> 1,
            "subgroup"=> null,
            "number"=> 3,
            "dayWeek"=> 4,
            "created_at"=> "2024-06-13T18:25:06.000000Z",
            "updated_at"=> "2024-06-13T18:25:06.000000Z",
            "subject"=> [
                "id"=> 1,
                "groupId"=> 1,
                "teacherSubjectId"=> 1,
                "created_at"=> "2024-06-13T16:25:48.000000Z",
                "updated_at"=> null,
                "teacher_subject"=> [
                    "id"=> 1,
                    "userTeacherId"=> 1,
                    "subjectId"=> 7,
                    "created_at"=> "2024-06-13T16:25:48.000000Z",
                    "updated_at"=> null,
                    "teacher"=> [
                        "id"=> 1,
                        "login"=> "popovDenisValentinovich",
                        "fio"=> "Попов Денис Валентинович",
                        "auditoriaId"=> 1,
                        "created_at"=> "2024-06-13T16:25:47.000000Z",
                        "updated_at"=> null
                    ],
                    "subject"=> [
                        "id"=> 7,
                        "title"=> "Основы Frontend"
                    ]
                ]
            ]
        ],200);
    }

    public function getCurrentLesson(Request $request) {
        $studentId = ($request->user)['id'];

        $student = UserStudent::with(['group.schedules.subject.teacherSubject.teacher.auditorium','group.schedules.subject.teacherSubject.subject'])->find($studentId);
        $subgroup = $student->subgroup;

        // Получение текущей даты и времени в Астане
        $currentDateTime = Carbon::now('Asia/Almaty');

        // Получение текущего дня недели (от 1 до 7)
        $dayOfWeek = $currentDateTime->dayOfWeekIso;

        $schedule = $student->group->schedules->filter(function ($item) use ($dayOfWeek, $subgroup) {
            return ($item['dayWeek'] == $dayOfWeek) && ($item['subgroup'] == $subgroup || $item['subgroup'] == null);
        })->sortBy('number')->values();

        $currentDateTime = Carbon::now('Asia/Almaty');
        $currentClass = self::getCurrentClass($currentDateTime);

        $desiredObject = $schedule->firstWhere('number', $currentClass);

        return response()->json($desiredObject ?? (str_contains($currentClass, '.5') ? ['status' => "перемена"] : ['status' => 'отдых']), 200);
    }

    public function getTodayShedule(Request $request) {
        $studentId = ($request->user)['id'];

        $student = UserStudent::with(['group.schedules.subject.teacherSubject.teacher','group.schedules.subject.teacherSubject.subject'])->find($studentId);
        $subgroup = $student->subgroup;


        // Получение текущей даты и времени в Астане
        $currentDateTime = Carbon::now('Asia/Almaty');

        // Получение текущего дня недели (от 1 до 7)
        $dayOfWeek = $currentDateTime->dayOfWeekIso;

        $schedule = $student->group->schedules->filter(function ($item) use ($dayOfWeek, $subgroup) {
            return ($item['dayWeek'] == $dayOfWeek) && ($item['subgroup'] == $subgroup || $item['subgroup'] == null);
        })->sortBy('number')->values()->all();

        return response()->json($schedule, 200);
    }
}
