<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // these are all optional fields 
    // name of the table
    protected $table ='students';
    //  primary key setting
    protected $primaryKey = 'id';
    // fillable items in the create method
    protected $fillable = ['name'];
    // hide elemnets in the response, just like the password for example
    protected $hidden = ['created_at'];
    // what you want to show in the response only
    protected $visible = ['name', 'id', 'updated_at'];

    public function studentLogs() {
        return $this->hasMany(StudenLog::class);
    }
   


}
