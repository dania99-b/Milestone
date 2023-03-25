<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
}
