<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable=[
        'id',
        'start_date',
        'end_date',
        'questions',
        ];
    public function guests()
    {
        return $this->belongsToMany(Guest::class,'guest_placement_tests')->withPivot('mark', 'level');
    }
    public function students()
    {
        return $this->belongsToMany(Guest::class,'student_placement_tests')->withPivot('mark', 'level');
    }
}
