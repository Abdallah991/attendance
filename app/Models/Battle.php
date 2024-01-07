<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;
    // name of the table
    protected $table = 'battles';
    // fillable items in the create method
    protected $fillable = [
        'date',
        'name'
    ];

    public function warriors()
    {
        return $this->belongsToMany(Warrior::class);
    }
}
