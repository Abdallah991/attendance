<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;


//! this Controller requires an emp_code to get the transctions for a specific bio time user
//? get attendance for a specific user
class AttendanceController extends Controller
{



    public function string_between_two_string($str, $starting_word, $ending_word)
    {
        $subtring_start = strpos($str, $starting_word);
        //Adding the starting index of the starting word to
        //its length would give its ending index
        $subtring_start += strlen($starting_word);
        //Length of our required sub string
        $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
        // Return the substring from the index substring_start of length size
        return substr($str, $subtring_start, $size);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Getting the attendance for a specific employer
    public function index()
    {
        // platform token to get the API Token
        $platformToken =  config('app.PLATFORM_TOKEN');
        // Token expires every 3 days
        $apiToken =  config('app.GRAPHQL_TOKEN');
        // return $apiToken;

        // return $apiToken;
        // students query
        $cohortStudents = <<<GQL
     query {
            event (where: {path: {_eq: "/bahrain/bh-module"}}) {
            users {
            login
            firstName: attrs(path: "firstName")
            lastName: attrs(path: "lastName")
            email: attrs(path: "email")
            phone: attrs(path: "Phone")
    }
  }
}
GQL;

        // get transactions of students in the cohort
        $firstProjectActivity = <<<GQL
     query {
            transaction
  (where: { path: {_regex: "/bahrain/bh-module/*"}}) 
  {

    user {
      login
      firstName: attrs(path: "firstName")
      lastName: attrs(path: "lastName")
      email: attrs(path: "email")
      phone: attrs(path: "Phone")
    }
    type
    object {
      name
    }
    createdAt
  }
}
GQL;


        // 
        // return Config::get('app.GRAPHQL_TOKEN');
        //  graph api call for students
        // return Config::get('app.GRAPHQL_TOKEN');
        $students = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // ! this token have to be recreated every 2 days
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $cohortStudents
        ]);

        //  graph api call to show all students transactions
        $firstProjectResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // ! this token have to be recreated every 2 days
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $firstProjectActivity
        ]);
        // get the token in an amazing way

        // return $apiToken;
        // ! This condition works when the token is expired
        // if ($firstProjectResponse->json()['errors']) {

        //     // $path = base_path('.env');
        //     // $test = file_get_contents($path);
        //     // $token = $this->string_between_two_string($test, "PLATFORM_TOKEN=", "\n");
        //     // return $token;
        //     $apiToken =
        //         // config('session');
        //         Http::withHeaders([
        //             'Accept' => 'application/json',
        //             'Content-Type' => 'application/json',
        //         ])->get('https://learn.reboot01.com/api/auth/token?token=' . $platformToken)->json();
        //     // set api token

        //     // return $apiToken;
        //     Config::set('app.GRAPHQL_TOKEN', $apiToken);
        //     Config::get('app.GRAPHQL_TOKEN');
        //     Config::set('app.GRAPHQL_TOKEN', $apiToken);
        //     return Config::get('app.GRAPHQL_TOKEN');
        //     Config::set('app.GRAPHQL_TOKEN', $apiToken);

        //     // config(['app.GRAPHQL_TOKEN' => $apiToken]);

        //     return config('app.GRAPHQL_TOKEN');
        //     // return "Please call the API again, The token has been refreshed";
        // }
        // ! This above should be done
        // get the data in json object
        // return $firstProjectResponse;
        $firstProjectArray = $firstProjectResponse->json()['data']['transaction'];
        // students of the cohort
        $studentsArray = $students->json()['data']['event'][0]['users'];
        $result = array();
        // 1- when the result array is empty, add the object
        // 2- If the result is not empty, check if the user already added, add up the index
        // 3- add the user
        foreach ($firstProjectArray as $transaction) {
            if (count($result) != 0) {
                for ($key = 0; $key < count($result); $key++) {
                    if ($result[$key]['login'] == $transaction['user']['login']) {
                        switch ($transaction['type']) {
                            case "up":
                                $result[$key]['transaction']++;
                                $result[$key]['up']++;
                                $result[$key]['createdAt'] = $transaction['createdAt'];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "down":
                                $result[$key]['transaction']++;
                                $result[$key]['down']++;
                                $result[$key]['createdAt'] = $transaction['createdAt'];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "skill_go":
                                $result[$key]['transaction']++;
                                $result[$key]['skill_go']++;
                                $result[$key]['createdAt'] = $transaction['createdAt'];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "skill_algo":
                                $result[$key]['transaction']++;
                                $result[$key]['skill_algo']++;
                                $result[$key]['createdAt'] = $transaction['createdAt'];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                        }

                        continue 2;
                    }
                }
            }

            switch ($transaction['type']) {
                case "up":
                    array_push(
                        $result,
                        [
                            'login' => $transaction['user']['login'],
                            'firstName' => $transaction['user']['firstName'],
                            'lastName' => $transaction['user']['lastName'],
                            'email' => $transaction['user']['email'],
                            'phone' => $transaction['user']['phone'],
                            'transaction' => 1,
                            'up' => 1,
                            'down' => 0,
                            'skill_go' => 0,
                            'skill_algo' => 0,
                            'projectAt' => $transaction['object']['name'],
                            'createdAt' => $transaction['createdAt']

                        ]
                    );

                    break;
                case "down":
                    array_push(
                        $result,
                        [
                            'login' => $transaction['user']['login'],
                            'firstName' => $transaction['user']['firstName'],
                            'lastName' => $transaction['user']['lastName'],
                            'email' => $transaction['user']['email'],
                            'phone' => $transaction['user']['phone'],
                            'transaction' => 1,
                            'up' => 0,
                            'down' => 1,
                            'skill_go' => 0,
                            'skill_algo' => 0,
                            'projectAt' => $transaction['object']['name'],
                            'createdAt' => $transaction['createdAt']

                        ]
                    );

                    break;
                case "skill_go":
                    array_push(
                        $result,
                        [
                            'login' => $transaction['user']['login'],
                            'firstName' => $transaction['user']['firstName'],
                            'lastName' => $transaction['user']['lastName'],
                            'email' => $transaction['user']['email'],
                            'phone' => $transaction['user']['phone'],
                            'transaction' => 1,
                            'up' => 0,
                            'down' => 0,
                            'skill_go' => 1,
                            'skill_algo' => 0,
                            'projectAt' => $transaction['object']['name'],
                            'createdAt' => $transaction['createdAt']

                        ]
                    );
                    break;
                case "skill_algo":
                    array_push(
                        $result,
                        [
                            'login' => $transaction['user']['login'],
                            'firstName' => $transaction['user']['firstName'],
                            'lastName' => $transaction['user']['lastName'],
                            'email' => $transaction['user']['email'],
                            'phone' => $transaction['user']['phone'],
                            'transaction' => 1,
                            'up' => 0,
                            'down' => 0,
                            'skill_go' => 0,
                            'skill_algo' => 1,
                            'projectAt' => $transaction['object']['name'],
                            'createdAt' => $transaction['createdAt']

                        ]
                    );
                    break;
            }
        }

        return $result;
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
    // ? get method with id of student to get attendance
    public function show($id)
    {
        //TODO: add support for start time and end time
        // you can define the response max size
        $pageSize = 1000;
        // get all transactions for a specific student and admin and staff
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token 48e111ccd207225fff4b28cc5f7e6d68acf6b479'
        ])->get('http://10.1.50.4/iclock/api/transactions/?emp_code='
            . $id .
            '&page_size='
            . $pageSize);

        // convert from string to array of students only
        $filteredArray = $response['data'];
        // return response
        // TODO: create a resource for the attendnace
        return [
            "data" => $filteredArray,
            "count" => $response->json()['count'],
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
