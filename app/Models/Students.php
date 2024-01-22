<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'firstname',
        'lastname',
        'othernames',
        'class_id',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function class()
    {
        return $this->belongTo(StudentClass::class,'class_id','id');
    }
}
