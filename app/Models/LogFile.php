<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFile extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table=  'log_files';
  
    protected $fillable=[
        'id',
        'action',
        'user_id',
        ];
        public function employee(){
			return $this->belongsTo(Employee::class);
		}
}
