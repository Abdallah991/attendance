<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    // use Searchable;
    use HasFactory;
    // table name
    protected $table = 'transactions';
    //  primary key setting
    protected $primaryKey = 'id';

    // students has many logs
    // you can access all the logs on the students from this function
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
