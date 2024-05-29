<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Group as GroupModel;
use App\Models\UserStudent as UserStudentModel;

class GroupController extends Controller
{
    // Создание группы
    public function create(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $valid_data = $request->validate([
            'title' => 'required|unique:groups,title',
            'departmentId' => 'reqired|exists:departments,id',
            'userTeacherId' => 'reqired|exists:user_teachers,id',
            'color' => 'required'
        ]);

        $group = GroupModel::create([
            'title'=>$valid_data['title'],
            'departmentId'=>$valid_data['departmentId'],
            'userTeacherId'=>$valid_data['userTeacherId'],
            'color'=>$valid_data['color']
        ]);

        return resposne()->json(['status'=>'success', 'statusMessage'=>'Группа успешно создана', 'data' => $group], 200);
    }

    // Назначение студента в группу
    public function addStudents(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $valid_data = $request->validate([
            'userId' => 'required|exists:user_students,id',
            'groupId' => 'reqired|exists:groups,id',
        ]);

        $student = UserStudent::where([
            'id'=>$valid_data['userId']
        ])->first();

        $student->groupId = $valid_data['groupId'];
        return resposne()->json(['status'=>'success', 'statusMessage'=>'Студент успешно добавлен в группу'], 200);
    }

    // Удаление студента из группы
    public function removeStudent(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $valid_data = $request->validate([
            'userId' => 'required|exists:user_students,id',
            'groupId' => 'reqired|exists:groups,id',
        ]);

        $student = UserStudent::where([
            'id'=>$valid_data['userId']
        ])->first();

        if ($student->groupId == $valid_data['groupId']) {
            $student->groupId = null;
            return resposne()->json(['status'=>'success', 'statusMessage'=>'Студент успешно удален из группы'], 200);
        }
        return response()->json(['status'=>'error', 'statusMessage'=>'Студент находится в другой группе', 'data'=>$student], 204);
    }

    // Список студентов на назначение
    public function availableStudents(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $student = UserStudentModel::where([
            'groupId'=>null
        ])->get();

        return resposne()->json(['status'=>'success', 'statusMessage'=>'Студенты', 'data' => $student], 200);
    }
}
