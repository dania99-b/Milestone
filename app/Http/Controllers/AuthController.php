<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guest;
use App\Models\LogFile;
use Illuminate\Http\Request;
use App\Events\NotificationRecieved;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WebSocketSuccessNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    public function sendWebSocketRequest()
    {
    // Send the WebSocket request here using Laravel WebSockets

    // Trigger the event to notify the user
    $user = User::find(auth()->user()->id);
   // event(new NotificationRecieved($user));

    // Trigger the notification
   // Notification::send($user, new WebSocketSuccessNotification());
    }

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $user_roles = $user->roles()->pluck('name');
      //  $user->notify(new WebSocketSuccessNotification('New order placed!'));
    //   Notification::send($user, new WebSocketSuccessNotification('you are logged in'));
    
       //event(new NotificationRecieved($user));
        return response()->json([
                'token' => $token,
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'roles' => $user_roles,
        ]);
      
        $log = new LogFile();
            $log->user_id = $user->id;
            $log->action = 'Logged In';
            $log->save();
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());

    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function refreshh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}