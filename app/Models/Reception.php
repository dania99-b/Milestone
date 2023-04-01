<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'employee_id'
        ];
        public $timestamps = false;
    public function user(){
        $this->belongsTo(User::class);
    }
    public function employee(){
        $this->belongsTo(Employee::class);
    }
}
