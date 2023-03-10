<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'incoming_photo',
        'home_photo',
        'status',
        'login_coordinates',
        'return_coordinates'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
