<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    // use Searchable;
    use HasFactory;

    // these are all optional fields 
    // name of the table
    protected $table = 'students';
    // fillable items in the create method
    protected $fillable = [
        'id', 'platformId', 'acadamicQualification', 'acadamicSpecialization', 'scholarship',

        'firstName', 'lastName', 'email', 'nationality', 'gender',

        'cohortId', 'supportedByTamkeen', 'gender', 'phone', 'dob',
        'cpr'

    ];

    //  primary key setting
    protected $primaryKey = 'id';
    // protected $with = array('studentLogs');

    // protected $fillable = ['name'];
    // hide elemnets in the response, just like the password for example
    // protected $hidden = ['created_at'];
    // what you want to show in the response only
    // protected $visible = ['name', 'id', 'updated_at','created_at'];

    // students has many logs
    // you can access all the logs on the students from this function
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // retun the cohort a student belongs to
    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}
