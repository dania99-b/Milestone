<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'employee_id',
        'action',
        'is_appear'
        ];
        public function employee(){
			return $this->belongsTo(Employee::class);
		}

}
