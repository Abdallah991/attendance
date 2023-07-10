<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


//! this Controller requires an emp_code to get the transctions for a specific bio time user
//? get attendance for a specific user
class AttendanceController extends Controller
{

    // ? Get Students Transactions and Activity
    // TODO: Move to another controller
    public function index()
    {
        // platform token to get the API Token from .env
        $platformToken =  config('app.PLATFORM_TOKEN');
        // !Token expires every 3 days
        $apiToken =  config('app.GRAPHQL_TOKEN');
        // get all students from cohort query
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

        // get transactions of students in the cohort for all projects
        $ProjectActivity = <<<GQL
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
        //  graph api call for students
        $students = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $cohortStudents
        ]);

        //  graph api call to show all students transactions in cohort
        $ProjectResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $ProjectActivity
        ]);

        // ! This condition works when the token is expired
        // TODO: Write a global function that gets the token for 01 graphql

        // get the data in json object
        $firstProjectArray = $ProjectResponse->json()['data']['transaction'];
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
                                $result[$key]['createdAt'] = explode("T", $transaction['createdAt'])[0];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "down":
                                $result[$key]['transaction']++;
                                $result[$key]['down']++;
                                $result[$key]['createdAt'] = explode("T", $transaction['createdAt'])[0];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "skill_go":
                                $result[$key]['transaction']++;
                                $result[$key]['skill_go']++;
                                $result[$key]['createdAt'] = explode("T", $transaction['createdAt'])[0];
                                $result[$key]['projectAt'] = $transaction['object']['name'];
                                break;
                            case "skill_algo":
                                $result[$key]['transaction']++;
                                $result[$key]['skill_algo']++;
                                $result[$key]['createdAt'] = explode("T", $transaction['createdAt'])[0];
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
                            'createdAt' => explode("T", $transaction['createdAt'])[0]

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
                            'createdAt' => explode("T", $transaction['createdAt'])[0]

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
                            'createdAt' => explode("T", $transaction['createdAt'])[0]

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
                            'createdAt' => explode("T", $transaction['createdAt'])[0]

                        ]
                    );
                    break;
            }
        }

        // TODO: Use better algorithm
        foreach ($studentsArray as $student) {
            $match = false;
            foreach ($result as $activeStudent) {
                if ($student['login'] == $activeStudent['login']) {
                    $match = true;
                }
            }
            if (!$match) {
                array_push($result, $student);
            }
        }

        return $result;
    }


    // TODO: Create User attendance
    public function create()
    {
    }
    public function store(Request $request)
    {
    }


    // ? get Attendance for specific user
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



    // ? is it possible to edit a certain attendance 
    // ! No 
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
    }


    // Delete a certain transaction
    public function destroy($id)
    {
    }
}
