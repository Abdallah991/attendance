<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;



class BioTimeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Getting the employees list from attendance system
    public function index()
    {
        // you can define the response size
        $pageSize = 10;
        // directly call the api using ::get
        // get all employees students and admin and staff
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        ])->get('http://10.1.50.4:80/personnel/api/employees/?page_size=' . $pageSize);

        // convert from string to array of students only
        $filteredArray = Arr::where($response['data'], function ($value, $key) {
            return $value['department']['id'] == 2;
        });
        // return response
        // TODO: create a resource for the attendnace
        return [
            "data" => $filteredArray,
            "count" => $response->json()['count'],
            // ? how the link looks like
            // ? can be constructed depending on the page number
            //"next": "http://10.1.50.4/personnel/api/employees/?page=2",
            "next" => $response->json()['next'],
            "previous" => $response->json()['previous'],
            "pages" => ceil($response->json()['count'] / 10)

        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // you can define the response size
        $pageSize = 1000;
        // directly call the api using ::get
        // get all employees students and admin and staff
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        ])->get('http://10.1.50.4:80/personnel/api/employees/?page_size=' . $pageSize);

        // convert from string to array of students only
        $filteredArray = Arr::where($response['data'], function ($value, $key) {
            return $value['department']['id'] == 2;
        });
        // return response
        // TODO: create a resource for the attendnace
        return [
            "data" => $filteredArray,
            "count" => $response->json()['count'],
            // ? how the link looks like
            // ? can be constructed depending on the page number
            //"next": "http://10.1.50.4/personnel/api/employees/?page=2",
            "next" => $response->json()['next'],
            "previous" => $response->json()['previous'],
            "pages" => ceil($response->json()['count'] / 10)

        ];
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
    public function show($id)
    {
        // you can define the response size
        $pageSize = 10;
        // implement next and previous paginations
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        ])->get('http://10.1.50.4:80/personnel/api/employees/?page=' . $id . '&page_size=' . $pageSize);


        $filteredArray = Arr::where($response['data'], function ($value, $key) {
            return $value['department']['id'] == 2;
        });
        // return response
        return [
            "data" => $filteredArray,
            "count" => $response->json()['count'],
            // ? how the link looks like
            // ? can be constructed depending on the page number
            //"next": "http://10.1.50.4/personnel/api/employees/?page=2",
            "next" => $response->json()['next'],
            "previous" => $response->json()['previous'],
            "pages" => ceil($response->json()['count'] / 10),



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
