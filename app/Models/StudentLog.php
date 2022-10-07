<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLog extends Model
{
    use HasFactory;

     // name of the table
     protected $table ='students_log';
     //  primary key setting
     protected $primaryKey = 'id';

    //  student log belongs to a student
     public function student() {
        return $this->belongsTo(Student::class);
     }
}
