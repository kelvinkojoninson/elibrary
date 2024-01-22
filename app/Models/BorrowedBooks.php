<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowedBooks extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'book_id',
        'borrow_date',
        'return_date',
        'returned_date',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class,'student_id','id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class,'book_id','book_id');
    }
}
