<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // these are all optional fields 
    // name of the table
    protected $table = 'students';
    // fillable items in the create method
    protected $fillable = [
        'first_name', 'last_name', 'email', 'nationality',
        'cohort', 'supported_by_tamkeen', 'gender', 'phone', 'dob'
    ];

    //  primary key setting
    protected $primaryKey = 'id';
    protected $with = array('studentLogs');

    // protected $fillable = ['name'];
    // hide elemnets in the response, just like the password for example
    // protected $hidden = ['created_at'];
    // what you want to show in the response only
    // protected $visible = ['name', 'id', 'updated_at','created_at'];

    // students has many logs
    // you can access all the logs on the students from this function
    public function studentLogs()
    {
        return $this->hasMany(StudentLog::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}
