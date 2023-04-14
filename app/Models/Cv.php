<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'guest_id',
        'file',
        'advertisment_id'
        ];
    public $timestamps = false;
    public function guest(){
        $this->belongsTo(Guest::class);
    }
    public function advertisement(){
        $this->belongsTo(Advertisment::class);
    }
}
