<?php


namespace App\Filters;
use Illuminate\Http\Request;



Class ApiFilter {
    // parameters you are allowed to filter on
    protected $safeParam = [
    ];

    // paramter that were transformed in the rosource 
    protected $columnMap = [

    ];

    // operator shortcuts 

    protected $operatorMap = [
        'eq'=>'=',
        'ne'=>'!=',
        'lt'=>'<',
        'lte'=>'<=',
        'gt'=>'>',
        'gte'=>'>=',

    ];

    // transform function is a generic function that takes 
    // 1- paramter to filter on.
    // 2- transformed attributed by the resource 
    // 3- operator value
    // and it sends back the filtered query

    function transform(Request $request) {
        // value to be returned
        $eloQuery = [];

        foreach ($this->safeParam as $pram => $operators) {

            $query = $request->query($pram);

            if(!isset($query)){
                continue;
            }

            $column = $this->columnMap[$pram]?? $pram;

            foreach ($operators as $operator) {
                if(isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];

                }
            }

        }

        return $eloQuery;

    }

}