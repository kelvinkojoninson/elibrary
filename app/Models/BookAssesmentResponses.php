<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookAssesmentResponses extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'book_id',
        'question_id',
        'response_text',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function book()
    {
        return $this->belongTo(Books::class,'book_id','id');
    }

    public function student()
    {
        return $this->belongTo(Students::class,'student_id','id');
    }

    public function question()
    {
        return $this->belongTo(BookAssesmentQuestions::class,'question_id','id');
    }
}
