<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable=[
        'id',
        'employee_id'
        ];
    public $timestamps = false;
    use HasFactory;
    public function user(){
        $this->belongsTo(User::class);
    }

    public function employee(){
        $this->belongsTo(Employee::class);
    }
    public function rate(){
        $this->hasMany(StudentRate::class);
    }
}
