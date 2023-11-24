<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    // use Searchable;
    use HasFactory;

    // these are all optional fields 
    // name of the table
    protected $table = 'comments';
    // fillable items in the create method
    protected $fillable = [
        'platformId', 'commentedBy', 'comment'
    ];

    //  primary key setting
    protected $primaryKey = 'id';
}
