<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class StudentFilter extends ApiFilter
{
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id' => ['eq', 'gt', 'lt'],
        'firstName' => ['eq'],
        'lastName' => ['eq'],
        'email' => ['eq'],
        'createdAt' => ['eq'],
        'updatedAt' => ['eq'],
        'cohortId' => ['eq', 'gt', 'lt'],
        'supportedByTamkeen' => ['eq', 'ne'],
        'nationality' => ['eq', 'ne'],
        'phone' => ['eq'],
        'dob' => ['eq', 'lte', 'gte', 'lt', 'gt'],

    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        'supportedByTamkeen' => 'supported_by_tamkeen',
        'cohortId' => 'cohort_id',
        'fcmToken' => 'fcm_token',
    ];
}
