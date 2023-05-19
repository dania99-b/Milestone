<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'employee_id',
        'experince_years',
        'schedules'
        ];
    public $timestamps = false;

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function rate(){
        return $this->hasMany(StudentRate::class);
    }
}
