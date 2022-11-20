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
        'permission' => ['eq', 'gt', 'gte', 'lt', 'lte'],
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
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        'fcmToken' => 'fcm_token',
        'joinDate' => 'join_date',
    ];
}
