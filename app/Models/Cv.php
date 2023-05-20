<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'file',
        'guest_id',
        'advertisment_id'
        ];
    public $timestamps = true;
    
    public function guest(){
        $this->belongsTo(Guest::class);
    }
    public function advertisement(){
        $this->belongsTo(Advertisment::class);
    }
}
