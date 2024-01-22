<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadedBooks extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'book_id',
        'term',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function student()
    {
        return $this->belongTo(Students::class,'student_id','id');
    }

    public function book()
    {
        return $this->belongTo(Books::class,'book_id','id');
    }
}
