<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Permission extends Model
{

    use Searchable;
    use HasFactory;

    // name of the table
    protected $table = 'permissions';
    //  primary key setting
    protected $primaryKey = 'id';

    //  a permissions belongs to a role
    // you can access the role from a permission
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
