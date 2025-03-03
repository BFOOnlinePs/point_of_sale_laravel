<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login
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
            // Check if subscription is active


            $token = $request->user()->createToken('api-token')->plainTextToken;
            // $user = User::find(auth()->user()->id);

            return response([
                'status' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                // 'user' => auth()->user(),
                'token' => $token,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'الرجاء التأكد من البيانات المدخلة'
            ], 401);
        }
    }
}
