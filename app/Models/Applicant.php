<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    // table name
    protected $table = 'applicants';
    // fillable items in the create method
    protected $fillable = [
        'platformId', 'firstName', 'lastName', 'email', 'phone', 'status', 'score', 'lastGameDate'
    ];

    protected $casts = [
        'dob' => 'date',
    ];
}
