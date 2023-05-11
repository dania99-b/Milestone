<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFile extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'action',
        'employee_id',
        ];
        public function employee(){
			return $this->belongsTo(Employee::class);
		}
}
