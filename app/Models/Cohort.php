<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    use HasFactory;
    // these are all optional fields 
    // name of the table
    protected $table = 'cohorts';
    // fillable items in the create method
    protected $fillable = [
        'name',
        'year',
    ];

    //  primary key setting
    protected $primaryKey = 'id';

    // TODO: create the relationships
}
