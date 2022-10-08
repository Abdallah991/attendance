<?php
namespace App\Filters;
use Illuminate\Http\Request;
use App\Filters\ApiFilter;



Class StudentLogFilter extends ApiFilter {
    // parameters you are allowed to filter on
    protected $safeParam = [
        'id'=> ['eq','gt','lt'],
        'studentId'=> ['eq'],
        'log'=> ['eq','gt','lt','gte','lte','ne'],
        'createdAt'=> ['eq'],
        'updatedAt'=> ['eq'],
    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [
        'createdAt'=> 'created_at',
        'updatedAt'=> 'updated_at',
        'studentId'=> 'student_id',
    ];

    

}