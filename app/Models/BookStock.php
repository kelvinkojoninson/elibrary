<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'transaction_type',
        'transaction_date',
        'quantity',
        'note',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function book()
    {
        return $this->belongTo(Books::class,'book_id','id');
    }
}
