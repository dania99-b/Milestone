<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'user_id',
        'image',
        ];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function teacher(){
        return $this->hasOne(Teacher::class);
    }

    public function reciption(){
        return $this->hasOne(Reception::class);
    }

    public function human_resource(){
       return $this->hasOne(HumanResource::class);
    }

   
    public function leave(){
        return $this->hasMany(LeaveAndResignation::class);
    }
}
