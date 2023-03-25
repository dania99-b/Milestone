<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable=[
        'id',
        'user_id'
        ];
    public $timestamps = false;
    use HasFactory;

    public function user(){
        $this->belongsTo(User::class);
    }

    public function teacher(){
        $this->hasOne(Teacher::class);
    }
    public function reciption(){
        $this->hasOne(Reception::class);
    }
    public function human_resource(){
        $this->hasOne(Human_Resource::class);
    }
}
