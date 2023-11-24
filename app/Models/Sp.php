<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp extends Model
{
    use HasFactory;

    // table name
    protected $table = 'sp';
    // fillable items in the create method
    protected $fillable = [
        'platformId', 'firstName', 'lastName', 'email', 'phone', 'xp', 'level', 'gender', 'nationality', 'profilePicture',
        'cprPicture', 'pictureChanged', 'dob', 'acadamicQualification', 'acadamicSpecialization', 'employment', 'howDidYouHear',
        'sp', 'progresses', 'registrations'
    ];

    protected $casts = [
        'dob' => 'date',
    ];
}
