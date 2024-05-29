<?php

namespace App\Http\Controllers\AdminPanel\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth as LaravelAuth;
use Illuminate\Support\Facades\Hash;

use App\Modules\SLAuthorization as SLAuthorization;


class AdminAuthController extends Controller
{
    public function signin(Request $request) {
        $valid_data = $request->validate([
            'login' => 'required|exists:user_admins,login',
            'password' => 'required',
        ]);

        $token = SLAuthorization::guard('admin')->attempt(['login'=>$valid_data['login'], 'password'=>$valid_data['password']]);

        if ($token) {
            return response()->json(['token'=>$token], 200);
        }

        return response()->json([], 400);
    }

    public function signout(Request $request) {

    }
}
