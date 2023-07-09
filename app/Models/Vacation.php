<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $table = 'vacations';
    // fillable items in the create method
    protected $fillable = [
        'platformId', 'studentId', 'startDate', 'endDate', 'description', 'author'
    ];

    //  primary key setting
    protected $primaryKey = 'id';
}
