<?php

namespace App\Http\Controllers;

use App\Models\GuestPlacement;
use App\Models\User;
use App\Models\Admin;
use App\Models\Guest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Reception;
use App\Models\TeacherSchedules;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HumanResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EmployeeRequest;
use App\Http\Requests\StudentRequest;
use App\Models\StudentPlacement;
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
    
        $validatedData = $request->validated();
    
        $mainuser = User::firstOrCreate([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'phone' => $validatedData['phone'],
            'username' => $validatedData['username'],
            'birth' => $validatedData['birth'],
        ]);
    
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image' => $upload
        ]);
    
        $teacher = Teacher::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
    
        if (isset($validatedData['schedules']) && is_array($validatedData['schedules'])) {
            $teacher->schedules = $validatedData['schedules'];
            $teacher->save();
        }
    
        $mainteacher = Teacher::updateOrCreate(
            ['employee_id' => $mainemployee->id],
            ['experince_years' => $request->experince_years]
        );
    
        $mainuser->attachRole('Teacher');
    
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], 200);
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
    public function currentGuest($email){
        $theGuest = Guest::where('email', $email)->get();
        dd($theGuest);
        if($theGuest){
            return response()->json(['data' => $theGuest], 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function getAllGuest(){ 
        $guests=Guest::all();
        return response()->json($guests,200);

    }
    public function searchGuestByEmail($email){ 
    $guest=Guest::where('email',$email)->get();
    return response()->json($guest,200);
    }

    public function student(StudentRequest $request,$id){    
        $guest=Guest::find($id);        
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        
        $mainstudent = User::firstOrCreate([
            'first_name' => $guest->first_name,
            'last_name' =>$guest->last_name,
            'email' =>$guest->email,
            'password' => bcrypt($request->validated()['password']),
            'phone' => $guest->phone,
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);
        
        $student = $mainstudent->student()->create([
            'user_id' => $mainstudent->id,
            'image' => $upload,
            'country_id' => $request['country_id'],
            'education'=>$guest->education
        ]);

      
        $guest_placement=GuestPlacement::where('guest_id',$id)->latest()->first();
        StudentPlacement::create([
            'test_id' => $guest_placement->test_id,
            'student_id'=>  $student->id,
            'mark'=>$guest_placement->mark,//request
            'created_at'=>$guest_placement->created_at,
            'updated_at'=>$guest_placement->updated_at,
            'level'=> $request['level'],


        ]);
        $guest_placement->delete();
        $guest->delete();
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