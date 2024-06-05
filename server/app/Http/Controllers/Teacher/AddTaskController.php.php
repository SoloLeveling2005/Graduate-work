<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ClassroomTask;
use App\Models\ClassroomTaskFile;
use App\Models\ClassroomTaskLink;

class AddTaskController extends Controller
{
    public function create(Request $request) {
        // TODO добавить проверку на доступ для пользователя

        // Валидация данных запроса
        $valid_data = $request->validate([
            'classroomId' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'file',
            'links' => 'nullable|array',
            'links.*' => 'string|url',
            'date' => 'nullable|date'
        ]);

        // Сохранение задачи
        $task = ClassroomTask::create([
            'classroomId' => $valid_data['classroomId'],
            'title' => $valid_data['title'],
            'description' => $valid_data['description'],
            'periodOfExecution' => $valid_data['date'] ?? null,
        ]);

        // Сохранение файлов, если они есть
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('uploads', 'public');
                ClassroomTaskFile::create([
                    'classroomTaskId' => $task->id,
                    'link' => $filePath,
                ]);
            }
        }

        // Сохранение ссылок, если они есть
        if (!empty($valid_data['links'])) {
            foreach ($valid_data['links'] as $link) {
                ClassroomTaskLink::create([
                    'classroomTaskId' => $task->id,
                    'link' => $link,
                ]);
            }
        }

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }
}
