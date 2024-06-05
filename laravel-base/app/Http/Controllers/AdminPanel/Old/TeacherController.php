<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // POST - Создание преподавателя.
    public function create(Request $request) {

        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $valid_data = $request->validate([
            'login' => 'required|exists:user_teachers,login',
            'password' => 'required',
            'fio' => 'required'
        ]);

        $teacher = UserTeacher::create([
            'login'=>$valid_data['login'],
            'password'=>Hash::make($valid_data['password']),
            'fio'=>$valid_data['fio'],
            'created_at'=>now()
        ]);

        return resposne()->json(['status'=>'success', 'statusMessage'=>''], 200);
    }
    // GET - Получение информации о преподавателе. Инфо, группа, куратор и т.д.
    public function info(Request $request, $teacher_id) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        $teacher = UserTeacher::where(['id'=>$teacher_id])->first();
        
        if ($teacher) {
            return resposne()->json(['status'=>'success', 'statusMessage'=>'', 'data'=>[
                'login'=>$teacher->login,
                'fio'=>$teacher->fio,
                'created_at'=>$teacher->created_at
            ]], 200);
        }
        return response()->json(['status'=>'error', 'statusMessage'=>'Пользователей не найден.'], 204);
    }
    // POST - Удалние преподавателя.
    public function delete(Request $request) {
        $user = $request->user;

        // Проверка на обладание правами редактирования пользователей.
        if (!AdminPrivilege::where(['userAdminId'=>$user['id'],'privilege'=>'UserManager'])->first()) {
            return response()->json(['status'=>'error', 'statusMessage'=>'У вас не хвататет прав. Необходимы права редактора пользователей.'], 403);
        }

        // Получаем данные о новом преподавателе.
        $valid_data = $request->validate([
            'login' => 'required|exists:user_teachers,login',
        ]);

        $teacher = UserTeacher::where(['login'=>$valid_data['login']])->first();

        if ($teacher) {
            $teacher->delete();
            return resposne()->json(['status'=>'success', 'statusMessage'=>'Студент успешно удален'], 200);
        }
        return response()->json(['status'=>'error', 'statusMessage'=>'Пользователей не найден.'], 204);
    }

}
