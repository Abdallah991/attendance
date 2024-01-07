<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warrior extends Model
{
    use HasFactory;
    // name of the table
    protected $table = 'warriors';
    // fillable items in the create method
    protected $fillable = [
        'platformId',
        'name',
        'oldScore', 'newScore', 'codeWarsId'
    ];



    public function battles()
    {
        return $this->belongsToMany(Battle::class);
    }
}
