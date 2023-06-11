<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class StudentController extends Controller
{
    //Register
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'password' => 'required|between:8,255|confirmed',
            // 'password_confirmation' => 'required',
        ]);

        //create data
        $student = new Student;

        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->phone_number = isset($request->phone_number) ?  $request->phone_number : "";

        $student->save();

        //send response
        return response()->json([
            'status' => 1,
            'message' => 'Student Registered Successful'
        ]);
    }

    //Login
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check student
        $student = Student::where("email", "=", $request->email)->first();

        if (isset($student->id)) {

            if (Hash::check($request->password, $student->password)) {
                // Create Token
                $token = $student->createToken("auth_token")->plainTextToken;

                // Send a response
                return response()->json([
                    'status' => 1,
                    'message' => 'Student Log in Successful',
                    'access_token' => $token
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Password do not match'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Student not found'
            ], 401);
        }
    }


    //Profile
    public function profile()
    {
        return response()->json([
            "status" => 1,
            "message" => "Student Profile Information",
            "data" => auth()->user(),

        ]);
    }

    //Logout
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "message" => "Student logged out Successfully"
        ]);
    }
}
