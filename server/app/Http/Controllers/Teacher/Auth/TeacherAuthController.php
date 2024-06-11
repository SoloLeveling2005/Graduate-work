<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserTeacher;
use Illuminate\Support\Facades\Hash;
use App\Modules\SLAuthorization as SLAuthorization;

class TeacherAuthController extends Controller
{
    public function signin(Request $request) {
        
        $valid_data = $request->validate([
            'login' => 'required|exists:user_teachers,login',
            'password' => 'required',
            'replace_password' => 'nullable|string|min:8', // Новое поле для замены пароля
            'replace_password_repeat' => 'nullable|string|min:8', // Новое поле для замены пароля повтор
        ]);

        $teacher = UserTeacher::where('login', $valid_data['login'])->first();

        if (isset($valid_data['replace_password']) && $valid_data['replace_password'] != $valid_data['replace_password_repeat']) {
            return response()->json(['error' => "Passwords don't match"], 400);
        }

        dd(Hash::check('popov12', Hash::make('popov12')));


        if (!$teacher || !Hash::check($valid_data['password'], $teacher->password)) {
            return response()->json(['error' => 'Invalid login or password'], 401);
        }

        // Проверка наличия поля replace_password
        if (isset($valid_data['replace_password'])) {
            $teacher->password = bcrypt($valid_data['replace_password']);
            $teacher->save();

            // Удаление всех старых токенов
            SLAuthorization::guard('teacher')->revokeAllTokens($teacher->id);
        }

        $token = SLAuthorization::guard('teacher')->attempt(['login' => $valid_data['login'], 'password' => $valid_data['replace_password'] ?? $valid_data['password']]);

        return response()->json($token, $token['status']);
    }

    public function signout(Request $request) {
        $revokeStatus = SLAuthorization::guard('teacher')->revoke($request);

        return response()->json($revokeStatus, $revokeStatus['status']);
    }
}
