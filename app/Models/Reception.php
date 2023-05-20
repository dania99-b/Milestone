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

    public $timestamps = true;

    public function employee(){
        $this->belongsTo(Employee::class);
    }
}
