<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class EventFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'title' => ['eq'],
        'capacity' => ['eq', 'lte', 'gte', 'lt', 'gt'],
        'date' => ['eq', 'lte', 'gte', 'lt', 'gt'],
        'time' => ['eq', 'lte', 'gte', 'lt', 'gt'],
        'location' => ['eq'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
}
