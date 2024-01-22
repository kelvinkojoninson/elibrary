<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookGenres extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'genre_id',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function genre()
    {
        return $this->belongsTo(Genres::class,'genre_id','id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class,'book_id','id');
    }
}
