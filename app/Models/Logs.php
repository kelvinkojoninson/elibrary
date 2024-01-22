<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'logs';

    protected $fillable = [
        'userid',
        'module',
        'action',
        'ipaddress',
        'country_name',
        'country_code',
        'region_name',
        'region_code',
        'city_name',
        'zip_code',
        'longitude',
        'latitude',
        'device',
        'request',
        'status',
        'station'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'userid','id');
    }
}
