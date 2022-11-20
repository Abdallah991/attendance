<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class CohortFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'name' => ['eq'],
        'year' => ['eq', 'lte', 'gte', 'lt', 'gt'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
}
