<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookAssesmentQuestions extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'question_text',
        'question_type',
        'options',
        'status',
        'createuser',
        'modifyuser'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function book()
    {
        return $this->belongTo(Books::class,'book_id','id');
    }
}
