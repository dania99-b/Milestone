<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanResource extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'employee_id'
        ];
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
