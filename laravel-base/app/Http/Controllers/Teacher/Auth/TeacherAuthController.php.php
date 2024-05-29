<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherAuthController extends Controller
{
    public function signin(Request $request) {
        $valid_data = $request->validate([
            'login' => 'required|exists:user_teachers,login',
            'password' => 'required',
        ]);

        $token = SLAuthorization::guard('teacher')->attempt(['login'=>$valid_data['login'], 'password'=>$valid_data['password']]);

        if ($token) {
            return response()->json(['token'=>$token], 200);
        }

        return response()->json([], 400);
    }

    public function signout(Request $request) {

    }
}
