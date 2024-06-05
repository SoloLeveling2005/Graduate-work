<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\GroupScheduleClassReplacementRequest;
use App\Models\GroupScheduleClassReplacement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReplacementController extends Controller
{
    /**
     * Получение запросов на замену.
     */
    public function requestsList()
    {
        $requests = GroupScheduleClassReplacementRequest::with(['teacher', 'scheduleClass'])->get();

        return response()->json($requests);
    }

    /**
     * Получение запросов на замену за текущий месяц или указанный в параметре.
     */
    public function requestsListMonth(Request $request)
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $requests = GroupScheduleClassReplacementRequest::with(['teacher', 'scheduleClass'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        return response()->json($requests);
    }

    /**
     * Отказать в замене.
     */
    public function rejectReplacement($replacementId)
    {
        $request = GroupScheduleClassReplacementRequest::findOrFail($replacementId);
        $request->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Одобрить замену.
     */
    public function confirmReplacement($replacementId)
    {
        $request = GroupScheduleClassReplacementRequest::findOrFail($replacementId);

        $replacement = GroupScheduleClassReplacement::create([
            'userTeacherId' => $request->userTeacherId,
            'groupScheduleClassId' => $request->groupScheduleClassId,
            'subgroup' => $request->subgroup,
            'reason' => $request->reason,
        ]);

        $request->delete();

        return response()->json(['success' => true, 'replacement' => $replacement]);
    }

    /**
     * Получение замен.
     */
    public function list()
    {
        $replacements = GroupScheduleClassReplacement::with(['teacher', 'scheduleClass'])->get();

        return response()->json($replacements);
    }
}
