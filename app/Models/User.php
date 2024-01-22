<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'name',
        'email',
        'phone',
        'picture',
        'gender',
        'country',
        'dob',
        'deactivation_reason',
        'role_id',
        'password',
        'two_step',
        'last_login',
        'status',
        'firebaseKey',
        'email_verified_at',
        'modifyuser',
        'createuser',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'userid', 'student_id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'createuser', 'userid');
    }

    public function modifyUser()
    {
        return $this->belongsTo(User::class, 'modifyuser', 'userid');
    }

    public function userLogs()
    {
        return $this->hasMany(Logs::class, 'userid', 'userid');
    }

    public function downloads()
    {
        return $this->hasMany(DownloadedBooks::class, 'userid', 'student_id');
    }

    public function favorites()
    {
        return $this->hasMany(FavoriteBooks::class, 'userid', 'student_id');
    }

    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class, 'userid', 'student_id');
    }

    public function borrowedBooks()
    {
        return $this->hasMany(BorrowedBooks::class, 'userid', 'student_id');
    }
}
