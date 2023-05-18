<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Reception;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HumanResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\StudentRequest;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class RegisterController extends Controller
{
    public function admin(EmployeeRequest $request){
        $upload = $request->file('images')->move('images/', $request->file('images')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);
        $mainemployee = Admin::firstOrCreate([
            'user_id' => $mainuser->id,
        ]);
        $mainuser->attachRole('Admin');
        return response()->json([
            'message' => 'admin successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], '200');
    }
    
    public function teacher(EmployeeRequest $request){
            $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $mainuser = User::firstOrCreate([
                'first_name' => $request->validated()['first_name'],
                'last_name' => $request->validated()['last_name'],
                'email' =>$request->validated()['email'],
                'password' => bcrypt($request->validated()['password']),
                'phone' => $request->validated()['phone'],
                'username' => $request->validated()['username'],
                'birth' => $request->validated()['birth'],
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image'=>$upload
        ]);
        $mainteacher = Teacher::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
        $mainuser->attachRole('Teacher');
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], '200');
    }

    public function reception(EmployeeRequest $request){
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'], 
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image'=>$upload
        ]);
        $mainreception = Reception::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
        $mainuser->attachRole('Reception');
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], '200');
    }

    public function HR(EmployeeRequest $request){
        $upload = $request->file('images')->move('images/', $request->file('images')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'image'=>$upload,
            'birth' => $request->validated()['birth'], 
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image'=>$upload
        ]);
        $mainreception = HumanResource::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
        $mainuser->attachRole('HR');
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
    
        ], '200');
    }

    public function student(StudentRequest $request){              
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $mainstudent = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
          
        ]);
        $student = $mainstudent->student()->create([
            'user_id' => $mainstudent->id,
            'image' => $upload,
            'country_id' => $request['country_id']
        ]);
        $mainstudent->attachRole('Student');
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainstudent,
            'token' => $mainstudent->createToken('tokens')->plainTextToken
        ], 200);
    }

    public function guestVertification(Request $request){
        $guest = Guest::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'education' => $request->education,
            'verification_code' => Str::random(4), // Generate a verification code
        ]);
        Mail::to($guest->email)->send(new \App\Mail\VerifyEmail($guest));
        return response()->json(['message' => 'User registered successfully. Please check your email to verify your account.'], 201);
    } 
    
    public function verify(Request $request){
        $guest = Guest::where('email', $request->email)
                       ->where('verification_code', $request->verification_code)
                       ->first();
        if ($guest) {
            $guest->save();
            return response()->json(['message' => 'Email verified successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid verification code.'], 400);
    }

    public function test(Request $request){
        $users = User::whereRoleIs('Admin')->get();
        return $users;
    }

    public function resend(Request $request){
        $guest = Guest::where('email', $request->email)->first();
        if ($guest) {
            $newVerificationCode = Str::random(4);
            $guest->verification_code = $newVerificationCode;
            $guest->save();
            Mail::to($guest->email)->send(new VerifyEmail($guest));
            return response()->json(['message' => 'New verification code sent.'], 200);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }
}