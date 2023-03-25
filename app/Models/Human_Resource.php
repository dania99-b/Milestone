<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Human_Resource extends Model
{
    protected $table = "human_resources";
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
}
