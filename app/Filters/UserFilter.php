<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class UserFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'firstName' => ['eq'],
        'lastName' => ['eq'],
        'email' => ['eq'],
        'roleId' => ['eq', 'gt', 'gte', 'lt', 'lte'],
        'createdAt' => ['eq'],
        'updatedAt' => ['eq'],
        'nationality' => ['eq', 'ne'],
        'phone' => ['eq'],
        'position' => ['eq'],
        'joinDate' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'dob' => ['eq', 'lte', 'gte', 'lt', 'gt'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'firstName' => 'firstName',
        'lastName' => 'lastName',
        'fcmToken' => 'fcmToken',
        'joinDate' => 'joinDate',
    ];
}
