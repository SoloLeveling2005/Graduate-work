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

        return null;
    }

    public function getCurrentLesson(Request $request) {
        $studentId = ($request->user)['id'];

        $student = UserStudent::with(['group.schedules'])->find($studentId);

        return response()->json($student, 200);
    }

    public function getTodayShedule(Request $request) {
        $student = $request->user;
        $studentId = $student['id'];

        
    }
}