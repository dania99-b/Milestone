<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_User extends Model
{
    protected $table ='role_user';
    protected $fillable = [
        'role_id',
        'user_id',
        'user_type',     
    ];
    use HasFactory;
}
