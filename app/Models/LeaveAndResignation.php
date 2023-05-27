<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAndResignation extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'employee_id',
        'reason',
        'file',
        'from',
        'to',
        'type'
        ];
        public function employee(){
            return $this->belongsTo(Employee::class);
        }
}
