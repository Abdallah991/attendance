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
        'platformId', 'firstName', 'lastName', 'email', 'phone', 'xp', 'level', 'lastActivity'
    ];

    protected $casts = [
        'dob' => 'date',
    ];
}
