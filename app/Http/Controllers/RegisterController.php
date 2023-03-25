<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guest;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Reception;
use Illuminate\Http\Request;
use App\Models\Human_Resource;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EmployeeRequest;
use App\Models\Student;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class RegisterController extends Controller
{

    public function RegisterAdmin(EmployeeRequest $request)
    {
       
       // $upload = $request->file('photo')->move('appimages/', $request->file('photo')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
           
        ]);
        $mainemployee = Admin::firstOrCreate([
            'user_id' => $mainuser->id
        ]);
       
          
    
        $mainuser->attachRole('Admin');
    
    
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
    
        ], '201');

}



    public function RegisterTeacher(EmployeeRequest $request)
    {
   
   // $upload = $request->file('photo')->move('appimages/', $request->file('photo')->getClientOriginalName());
    $mainuser = User::firstOrCreate([
        'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
       
    ]);
    $mainemployee = Employee::firstOrCreate([
        'user_id' => $mainuser->id
    ]);
    $mainteacher = Teacher::firstOrCreate([
        'employee_id' => $mainemployee->id,
    ]);
      

    $mainuser->attachRole('Teacher');


    return response()->json([
        'message' => 'user successfully registered',
        'user' => $mainuser,
        'token' => $mainuser->createToken('tokens')->plainTextToken

    ], '201');


}

public function RegisterReception(EmployeeRequest $request)
    {

        
   
   // $upload = $request->file('photo')->move('appimages/', $request->file('photo')->getClientOriginalName());
    $mainuser = User::firstOrCreate([
          'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
       
    ]);
    $mainemployee = Employee::firstOrCreate([
        'user_id' => $mainuser->id
    ]);
    $mainreception = Reception::firstOrCreate([
        'employee_id' => $mainemployee->id,
    ]);
      

    $mainuser->attachRole('Reception');


    return response()->json([
        'message' => 'user successfully registered',
        'user' => $mainuser,
        'token' => $mainuser->createToken('tokens')->plainTextToken

    ], '201');


}


public function RegisterHR(EmployeeRequest $request)
    {
       
       // $upload = $request->file('photo')->move('appimages/', $request->file('photo')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
           
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id
        ]);
        $mainreception = Human_Resource::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
          
    
        $mainuser->attachRole('HR');
    
    
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
    
        ], '201');

}

public function RegisterStudent(EmployeeRequest $request)
    {
       
        $mainstudent = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' =>$request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
           
        ]);
        $student = Student::firstOrCreate([
            'user_id' => $mainstudent->id
        ]);
       
          
    
        $mainstudent->attachRole('Student');
    
    
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainstudent,
            'token' => $mainstudent->createToken('tokens')->plainTextToken
    
        ], '201');




    }




    public function GuestVertification(Request $request)
    {
        $guest = Guest::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
           // 'question_list_id'=>3,
            'verification_code' => Str::random(4), // Generate a verification code
        ]);

        // Send the verification code to the user's email
      //  Mail::send('emails.verification_code', ['code' => $guest->verification_code], function ($message) use ($guest) {
      //      $message->to($guest->email)->subject('Verify Your Email Address');
       // });
        Mail::to($guest->email)->send(new \App\Mail\VerifyEmail($guest));
       // Mail::to($guestEmail)->send(new EmailVerificationCode($verificationCode));
        // Return a success response
        return response()->json(['message' => 'User registered successfully. Please check your email to verify your account.'], 201);
    } 
    
    public function verifyEmail(Request $request)
    {
        // Find the user by email and verification code
        $guest = Guest::where('email', $request->email)
                    ->where('verification_code', $request->verification_code)
                    ->first();

        // If the user exists, mark their email as verified and log them in
        if ($guest) {
           // $guest->markEmailAsVerified();
          // $guest->verification_code = null;
            $guest->save();

            // Log the user in
          //  Auth::login($guest);

            // Return a success response
            return response()->json(['message' => 'Email verified successfully.'], 200);
        }

        // Return an error response
        return response()->json(['message' => 'Invalid verification code.'], 400);
    }
    public function test(Request $request)
    {
    $users = User::whereRoleIs('Admin')->get();
    return $users;
    }

    


}