<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // these are all optional fields 
    // name of the table
    protected $table = 'events';
    // fillable items in the create method
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'image',
    ];

    //  primary key setting
    protected $primaryKey = 'id';

    // TODO: create the relationships
}
