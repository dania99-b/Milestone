<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationFile extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        ];
        public function types(){
            $this->belongsTo(FileTypes::class);
        }
}
