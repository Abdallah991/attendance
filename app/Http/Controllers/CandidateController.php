<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



class CandidateController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Getting the attendance for a specific employer
    public function index($id)
    {
        // // you can define the response size
        // $pageSize = 200;
        // // get all transactions for a specific student and admin and staff
        // $response = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        // ])->get('http://10.1.50.4/iclock/api/transactions/?emp_code=' . $id . '&page_size=' . $pageSize);

        // // convert from string to array of students only
        // $filteredArray = Arr::where($response['data'], function ($value, $key) {
        //     return $value['department']['id'] == 2;
        // });
        // // return response
        // // TODO: create a resource for the attendnace
        // return [
        //     "data" => $filteredArray,
        //     "count" => $response->json()['count'],
        // ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCohortRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    //? get specific student info using emp_code/student id
    public function show($id)
    {
        // you can define the response size
        // get all transactions for a specific student and admin and staff
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        ])->get('http://10.1.50.4//personnel/api/employees/?emp_code='
            . $id);

        // convert from string to array of students only
        $candidate = $response['data'];
        // return response
        // TODO: create a resource for the attendnace
        return [
            "data" => $candidate,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCohortRequest  $request
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
