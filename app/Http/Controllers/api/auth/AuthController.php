<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ], [
            'phone.required' => 'الرجاء إدخال رقم الهاتف',
            'password.required' => 'الرجاء إدخال كلمة المرور',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $credentials = $validator->validated();

        if (Auth::attempt($credentials)) {

            $user = User::find(Auth::user()->id);
            Log::info($user);
            $subscription = $user->subscription;
            if (!$subscription || !$subscription->isActive() || !$subscription->isOngoing()) {
                return response([
                    'status' => false,
                    'message' => 'الاشتراك غير مفعل أو منتهي، الرجاء التواصل مع الدعم الفني'
                ], 401);
            }

            Log::info('user:' . $request->user());
            $token = $request->user()->createToken('api-token')->plainTextToken;
            // print user by token

            return response([
                'status' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' =>  $user->load('subscription'),
                'token' => $token,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'الرجاء التأكد من البيانات'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'status' => true,
            'message' => 'تم تسجيل الخروج بنجاح'
        ], 200);
    }


    // for the current authenticated user
    public function getUserInfo()
    {
        $user = User::find(Auth::user()->id);
        $user = $user->load('subscription');


        return response([
            'status' => true,
            'user' => $user,
        ], 200);
    }
}
