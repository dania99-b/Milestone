<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = true;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'username',
        'birth',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function employee(){
        return $this->hasOne(Employee::class);
    }

    public function admin(){
       return  $this->hasOne(Admin::class);
    }
    public function log(){
        return $this->hasMany(LogFile::class);
    }

    public function student(){
        return $this->hasOne(Student::class);
    }
    
    public function humanResource(){
        return $this->hasOne(HumanResource::class);
    }
    public function fcmtokens(){
        return $this->hasMany(fcmToken::class);
    }
}
