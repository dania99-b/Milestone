<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'user_id'
        ];

    public $timestamps = true;

    public function user(){
        $this->belongsTo(User::class);
    }
}