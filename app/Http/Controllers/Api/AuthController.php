<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Models\Notificationtable;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Student;
use Auth;
use Illuminate\Support\Str;

class AuthController extends BaseApiController
{
    
    public function login(Request $request)
    {
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $student = Student::where('email', request('email'))->first();

        if (!$student || !Hash::check(request('password'), $student->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $student->createToken(request('email'))->plainTextToken;

        // $notification = Notificationtable::where('school_id', '=', $student->classtable->school_id)->get();
        return $this->sendResponse([
            'token' => $token,
            'user_id' => $student->id,
            'user_email' => $student->email,
            'user_name' => $student->name,
            // 'notification' => $notification
        ]);
    }
  
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'User logged out!');
        
    }
}
