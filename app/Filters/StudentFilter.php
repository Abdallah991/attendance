<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class StudentFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'platfromId' => ['eq', 'gt', 'lt'],
        'firstName' => ['eq'],
        'lastName' => ['eq'],
        'email' => ['eq'],
        'createdAt' => ['eq'],
        'updatedAt' => ['eq'],
        'cohortId' => ['eq', 'gt', 'lt'],
        'supportedByTamkeen' => ['eq', 'ne'],
        'scholarship' => ['eq', 'ne'],
        'nationality' => ['eq', 'ne'],
        'phone' => ['eq'],
        'dob' => ['eq', 'lte', 'gte', 'lt', 'gt'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'firstName' => 'firstName',
        'lastName' => 'lastName',
        'supportedByTamkeen' => 'supportedByTamkeen',
        'cohortId' => 'cohortId',
        'fcmToken' => 'fcmToken',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
}
