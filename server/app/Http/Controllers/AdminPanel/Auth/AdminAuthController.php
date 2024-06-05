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

        return response()->json($token, $token['status']);

    }

    public function signout(Request $request) {

        $revokeStatus = SLAuthorization::guard('admin')->revoke($request);

        return response()->json($revokeStatus, $revokeStatus['status']);

    }
}
