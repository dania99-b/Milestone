<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Guest;
use App\Models\Period;
use App\Models\LogFile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Mail\VerifyEmail;
use App\Models\Reception;
use Illuminate\Support\Str;
use Termwind\Components\Hr;
use Illuminate\Http\Request;
use App\Models\HumanResource;
use App\Models\TeacherPeriod;
use App\Models\GuestPlacement;
use App\Models\StudentPlacement;
use App\Models\TeacherSchedules;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\Cv;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Exception;

class RegisterController extends Controller
{
    public function admin(EmployeeRequest $request)
    {
       // $upload = $request->file('images')->move('images/', $request->file('images')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' => $request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);
        $mainemployee = Admin::firstOrCreate([
            'user_id' => $mainuser->id,
        ]);
        $mainuser->attachRole('Admin');
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Register Admin';
            $log->save();
        return response()->json([
            'message' => 'admin successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], '200');
       
        
    }

    public function teacher(EmployeeRequest $request)
    {
        $periods = $request->validated()['period_id'];
        $periodIds = [];
        

        foreach ($periods as $period) {
            $periodModel = Period::find($period);
            $periodIds[] = $periodModel->id;
        }
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
      
        
        $teacher = Teacher::Create([
            'employee_id' => $mainemployee->id,
           
        ]);

        foreach ($periodIds as $period) {
      
            $newperiod_class=TeacherPeriod::Create([
                'teacher_id' => $teacher->id,
                'period_id' => $period,
                'is_occupied'=>0
            ]);
            $newperiod_class->save();
        }

        $mainteacher = Teacher::updateOrCreate(
            ['employee_id' => $mainemployee->id],
            ['experince_years' => $request->experince_years]
        );

        $mainuser->attachRole('Teacher');
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Register Teacher';
            $log->save();

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], 200);
    }


public function deleteTeacher($id)
{
    $teacher = Teacher::find($id);

    if (!$teacher) {
        return response()->json(['message' => 'Teacher not found'], 404);
    }
    $teacher->delete();
    $teacher->employee->delete();
    $teacher->employee->user->delete();
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
        $log->user_id = $user->id;
        $log->action = 'Delete Teacher';
        $log->save();

    return response()->json(['message' => 'Teacher deleted successfully'], 200);
}


    public function reception(EmployeeRequest $request)
    {
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' => $request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image' => $upload
        ]);
        $mainreception = Reception::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
        $mainuser->attachRole('Reception');
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Register Reception';
            $log->save();
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken
        ], '200');
    }

    public function deleteReception($id)
{
    $reception = Reception::find($id);

    if (!$reception) {
        return response()->json(['message' => 'Reception not found'], 404);
    }

    $reception->delete();
    $reception->employee->delete();
    $reception->employee->user->delete();
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
        $log->user_id = $user->id;
        $log->action = 'Delete Reception';
        $log->save();

    return response()->json(['message' => 'Reception deleted successfully'], 200);
}

    public function HR(EmployeeRequest $request)
    {
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
        $mainuser = User::firstOrCreate([
            'first_name' => $request->validated()['first_name'],
            'last_name' => $request->validated()['last_name'],
            'email' => $request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
            'phone' => $request->validated()['phone'],
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);
        $mainemployee = Employee::firstOrCreate([
            'user_id' => $mainuser->id,
            'image' => $upload
        ]);
        $mainreception = HumanResource::firstOrCreate([
            'employee_id' => $mainemployee->id,
        ]);
        $mainuser->attachRole('HR');
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Register Hr';
            $log->save();
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainuser,
            'token' => $mainuser->createToken('tokens')->plainTextToken

        ], '200');
    }
    public function deleteHr($id)
{
    $hr = HumanResource::find($id);

    if (!$hr) {
        return response()->json(['message' => 'Hr not found'], 404);
    }
    $hr->delete();
    $hr->employee->delete();
    $hr->employee->user->delete();
  
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
        $log->user_id = $user->id;
        $log->action = 'Delete Hr';
        $log->save();
    return response()->json(['message' => 'Hr deleted successfully'], 200);
}
    public function currentGuest($email)
    {
        $theGuest = Guest::where('email', $email)->get();
        dd($theGuest);
        if ($theGuest) {
            return response()->json(['data' => $theGuest], 200);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function getAllGuest()
    {
        $guests = Guest::with('tests')->get();
        return response()->json($guests, 200);
    }
    public function searchGuestByEmail($email)
    {
        $guest = Guest::where('email', $email)->get();
        return response()->json($guest, 200);
    }

    public function student(StudentRequest $request, $id)
    {
        $guest = Guest::find($id);
        $upload = $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());

        $mainstudent = User::firstOrCreate([
            'first_name' => $guest->first_name,
            'last_name' => $guest->last_name,
            'email' => $guest->email,
            'password' => bcrypt($request->validated()['password']),
            'phone' => $guest->phone,
            'username' => $request->validated()['username'],
            'birth' => $request->validated()['birth'],
        ]);

        $student = $mainstudent->student()->create([
            'user_id' => $mainstudent->id,
            'image' => $upload,
            'country_id' => $request['country_id'],
            'education' => $guest->education
        ]);


        $guest_placement = GuestPlacement::where('guest_id', $id)->latest()->first();
        StudentPlacement::create([
            'test_id' => $guest_placement->test_id,
            'student_id' =>  $student->id,
            'mark' => $guest_placement->mark, //request
            'level' => $request['level'], 
            'created_at' => $guest_placement->created_at,
            'updated_at' => $guest_placement->updated_at,
        ]);

        $checkCv = Cv::where('guest_id', $id)->get();

        foreach ($checkCv as $one) {
         
            $one->guest_id = null;
            $one->student_id =  $student->id; 
            $one->save(); 
        }

        $guest_placement->delete();
        $guest->delete();
        $mainstudent->attachRole('Student');
        $user = JWTAuth::parseToken()->authenticate();
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Register Student';
            $log->save();
        return response()->json([
            'message' => 'user successfully registered',
            'user' => $mainstudent,
            'token' => $mainstudent->createToken('tokens')->plainTextToken
        ], 200);
    }

    public function guestVertification(Request $request)
    {
        $existingGuest = Guest::where('email', $request->email)->first();
    
        if ($existingGuest) {
            return response()->json(['message' => 'Email is already registered.'], 400);
        }
    
        $guest = Guest::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'education' => $request->education,
            'verification_code' => Str::random(4), 
            'device_id'=> $request->device_id
        ]);
    
        Mail::to($guest->email)->send(new \App\Mail\VerifyEmail($guest));
        return response()->json(['message' => 'User registered successfully. Please check your email to verify your account.'], 201);
    }
    
    

    
    public function verify(Request $request)
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

        $token = $request->bearerToken();
        $guest = JWTAuth::toUser($token);

        if ($guest) {
            return response()->json($guest, 200);
        } else {
            return response()->json(['message' => 'Invalid token.'], 400);
        }
    }



    public function resend(Request $request)
    {
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
    public function subAdmin(Request $request)
     {
        $roles = $request->input('roles');
    $mainuser = User::firstOrCreate([
        'first_name' => $request['first_name'],
        'last_name' => $request['last_name'],
        'email' => $request['email'],
        'password' => bcrypt($request['password']),
        'phone' => $request['phone'],
        'username' => $request['username'],
        'birth' => $request['birth'],
    ]);
    $mainemployee = Admin::firstOrCreate([
        'user_id' => $mainuser->id,
    ]);
    foreach ($roles as $role) {
        $mainuser->attachRole($role);
    }
    $user = JWTAuth::parseToken()->authenticate();
    $log = new LogFile();
        $log->user_id = $user->id;
        $log->action = 'Register Admin';
        $log->save();
    return response()->json([
        'message' => 'admin successfully registered',
        'user' => $mainuser,
        'token' => $mainuser->createToken('tokens')->plainTextToken
    ], '200');
   
}}
