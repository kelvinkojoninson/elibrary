<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Books extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'title',
        'slug',
        'description',
        'transcript',
        'author',
        'category_id',
        'publication_year',
        'isbn',
        'language',
        'publisher',
        'pages',
        'country',
        'filepath',
        'cover_picture',
        'is_ebook',
        'ebook_format',
        'shelf_id',
        'call_number',
        'condition',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function category()
    {
        return $this->belongsTo(BookCategories::class,'category_id','id');
    }

    public function shelf()
    {
        return $this->belongsTo(Shelves::class,'shelf_id','id');
    }

    public function genres()
    {
        return $this->hasMany(BookGenres::class,'book_id','book_id');
    }
}
