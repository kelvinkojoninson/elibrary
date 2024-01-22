<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genres extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'picture',
        'status',
        'createuser',
        'modifyuser'
    ];

    public function books()
    {
        return $this->hasMany(BookGenres::class,'genre_id','id');
    }
}
