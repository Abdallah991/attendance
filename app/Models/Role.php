<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // use Searchable;
    use HasFactory;
    // these are all optional fields 
    // name of the table
    protected $table = 'roles';
    //  primary key setting
    protected $primaryKey = 'id';



    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
