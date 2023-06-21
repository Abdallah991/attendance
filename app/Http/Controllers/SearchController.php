<?php

namespace App\Http\Controllers;

// TODO: search api
use App\Http\Requests\SearchRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SearchController extends Controller
{



    // Getting the employees list from attendance system
    //? get first page of users on biotime
    public function index($request)
    {

        // var_dump();
        return "index";
    }




    // public function store(Request $request)
    // {
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    //? search from the bio time directly
    public function searchStudents(Request $request)
    {
        // ! V1 of search functuonality
        // // get the search value
        // $searchValue = $request->searchValue;
        // $pageSize = 1000;
        // // get all employees students and admin and staff
        // $response = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        // ])->get('http://10.1.50.4:80/personnel/api/employees/?page_size=' . $pageSize);

        // // convert from string to array of students only
        // $usersLength = count($response['data']);

        // // loop over all the records
        // // TODO: Implement better code for searching using one of ways to reduce o(n)
        // for ($i = 1; $i < $usersLength; $i++) {
        //     if (str_contains(strtolower($response['data'][$i]['full_name']), strtolower($searchValue)))
        //         array_push($filteredArray, $response->json()['data'][$i]);
        // }


        // // paginated response
        // return [
        //     "data" => $filteredArray,
        //     "count" => count($filteredArray),
        //     "pages" => ceil(count($filteredArray) / 10)

        // ];

        // TODO: V2 of Search function 
        $filteredArray = [];
        $searchValue = $request->searchValue;
        $students = Student::all();


        // return $request;
        for ($i = 0; $i < count($students); $i++) {

            // making sure that our search considers capital and lowercase characters
            // search on the
            // 1- email
            // 2- first name 
            // 3- last name 
            // 4- cpr
            // 5- platformId
            if (
                str_contains(strtolower($students[$i]['email']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['firstName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['firstName']) . ' ' . strtolower($students[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['cpr']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['platformId']), strtolower($searchValue))
            ) {

                array_push($filteredArray, $students[$i]);
            }
        }

        return $filteredArray;
    }


    function userExists($user, $searchValue)
    {
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
        return "edit";
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
        return "update";
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
