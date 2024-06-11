<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserStudent;
use Illuminate\Support\Facades\Hash;
use App\Modules\SLAuthorization as SLAuthorization;

class StudentAuthController extends Controller
{
    public function signin(Request $request) {
        
        $valid_data = $request->validate([
            'login' => 'required|exists:user_students,login',
            'password' => 'required',
            'replace_password' => 'nullable|string|min:8', // Новое поле для замены пароля
            'replace_password_repeat' => 'nullable|string|min:8', // Новое поле для замены пароля повтор
        ]);

        $student = UserStudent::where('login', $valid_data['login'])->first();

        if (isset($valid_data['replace_password']) && $valid_data['replace_password'] != $valid_data['replace_password_repeat']) {
            return response()->json(['error' => "Passwords don't match"], 400);
        }

        if (!$student || !Hash::check($valid_data['password'], $student->password)) {
            return response()->json(['error' => 'Invalid login or password'], 401);
        }

        // Проверка наличия поля replace_password
        if (isset($valid_data['replace_password'])) {
            $student->password = bcrypt($valid_data['replace_password']);
            $student->save();

            // Удаление всех старых токенов
            SLAuthorization::guard('student')->revokeAllTokens($student->id);
        }

        $token = SLAuthorization::guard('student')->attempt(['login' => $valid_data['login'], 'password' => $valid_data['replace_password'] ?? $valid_data['password']]);

        return response()->json($token, $token['status']);
    }

    public function signout(Request $request) {
        $revokeStatus = SLAuthorization::guard('student')->revoke($request);

        return response()->json($revokeStatus, $revokeStatus['status']);
    }
}
