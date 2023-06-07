<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{

    // use Searchable;
    use HasFactory;
    // these are all optional fields 
    // name of the table
    protected $table = 'cohorts';
    // fillable items in the create method
    protected $fillable = [
        'name',
        'year',
        'school'
    ];

    //  primary key setting
    protected $primaryKey = 'id';

    // TODO: create the relationships
    // !permission: 
    // 1- User needs admin and to type their password to be able to delete a cohort



    // a cohort has many students
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
