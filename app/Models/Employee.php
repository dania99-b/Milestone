<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable=[
        'id',
        'user_id',
        'image'
        ];
    public $timestamps = false;
    use HasFactory;

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
       return $this->hasOne(Human_Resource::class);
    }
}
