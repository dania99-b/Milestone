<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileTypes extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name'
    ];
    public function files(){
        $this->hasMany(EducationFile::class);
    }

}
