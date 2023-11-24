<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class CommentFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'platfromId' => ['eq', 'gt', 'lt'],
        'commentedBy' => ['eq', 'gt', 'lt'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
}
