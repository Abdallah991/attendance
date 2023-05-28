<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Role extends Model
{
    use Searchable;
    use HasFactory;
    // these are all optional fields 
    // name of the table
    protected $table = 'roles';
    //  primary key setting
    protected $primaryKey = 'id';
    // fillable items in the create method
    // protected $fillable = ['name'];
    // what you want to show in the response only
    // protected $visible = ['name', 'id', 'updated_at', 'created_at'];

    // a role has many permissions
    // you can access all the permissions related to the role
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
