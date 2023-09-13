<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


//! this Controller requires an emp_code to get the transctions for a specific bio time user
//? get attendance for a specific user
class StatisticsController extends Controller
{

    // ? Get Students Transactions and Activity
    public function studentsProgress()
    {


        // platform token to get the API Token from .env
        $platformToken =  config('app.PLATFORM_TOKEN');
        // !Token expires every 3 days
        $apiToken =  config('app.GRAPHQL_TOKEN');
        // get all students from cohort query
        $cohortStudents = <<<GQL
     query {
            event (where: {path: {_eq: "/bahrain/bh-module"}}) {
                usersRelation {
      level
      userAuditRatio
      user {
        login
      firstName: attrs(path: "firstName")
      lastName: attrs(path: "lastName")
      email: attrs(path: "email")
      phone: attrs(path: "Phone")
      }
      
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

        // return $students;

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
        $studentsArray = $students->json()['data']['event'][0]['usersRelation'];
        // return $studentsArray;
        $result = array();
        // 1- when the result array is empty, add the object
        // 2- If the result is not empty, check if the user already added, add up the index
        // 3- add the user
        foreach ($firstProjectArray as $transaction) {
            if (count($result) != 0) {
                for ($key = 0; $key < count($result); $key++) {
                    if ($result[$key]['login'] == $transaction['user']['login']) {
                        // return $transaction['object']['name'];
                        switch ($transaction['type']) {
                            case "up":
                                $result[$key]['transaction']++;
                                $result[$key]['up']++;
                                $result[$key]['auditDate'] = explode("T", $transaction['createdAt'])[0];
                                $result[$key]['lastProjectGaveAuditTo'] = $transaction['object']['name'];
                                break;
                            case "down":
                                $result[$key]['transaction']++;
                                $result[$key]['down']++;
                                $result[$key]['auditDate'] = explode("T", $transaction['createdAt'])[0];
                                $result[$key]['progressAt'] = $transaction['object']['name'];
                                $result[$key]['lastProjectGaveAuditTo'] = '-';

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
                            'lastProjectGaveAuditTo' => $transaction['object']['name'],
                            'progressAt' => '-',
                            'auditDate' => explode("T", $transaction['createdAt'])[0]

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
                            'progressAt' => $transaction['object']['name'],
                            'auditDate' => explode("T", $transaction['createdAt'])[0]

                        ]
                    );

                    break;
            }
        }

        // TODO: Use better algorithm
        // TODO: add the level
        // ? desired logic
        // same as below but
        // 1-remove time complexity to be O(n)
        // 2- add level and 


        //? current logic
        // loop over each student which have the level, audit ratio and user information
        foreach ($studentsArray as $student) {
            // set match valve to be false
            $match = false;
            // loop over the result students who had any transaction
            foreach ($result as $activeStudent) {
                // if any student  who had a transaction in the students array
                if ($student['user']['login'] == $activeStudent['login']) {
                    // they matched
                    $match = true;
                }
            }
            // if they didnt match add the student to the result array to have all students as response
            if (!$match) {
                array_push($result, [
                    'login' => $student['user']['login'],
                    'firstName' => $student['user']['firstName'],
                    'lastName' => $student['user']['lastName'],
                    'email' => $student['user']['email'],
                    'phone' => $student['user']['phone'],
                    'transaction' => 0,
                    'up' => 0,
                    'down' => 0,
                    'lastProjectGaveAuditTo' => '-',
                    'auditDate' =>  '-',
                    'progressAt' =>  '-',
                    'level' => 0,
                    'userAuditRatio' => '-',
                ]);
            }
        }

        $finalResult = [];
        // o(n2)
        foreach ($result as $student) {
            // return $student;
            foreach ($studentsArray as $leveledStudent) {
                if ($leveledStudent['user']['login'] == $student['login']) {

                    // return $student;
                    // try {
                    array_push($finalResult, [
                        'login' => $student['login'],
                        'firstName' => $student['firstName'],
                        'lastName' => $student['lastName'],
                        'email' => $student['email'],
                        'phone' => $student['phone'],
                        'transaction' => $student['transaction'],
                        'up' => $student['up'],
                        'down' => $student['down'],
                        'lastProjectGaveAuditTo' => $student['lastProjectGaveAuditTo'],
                        'auditDate' =>  $student['auditDate'],
                        'progressAt' =>  $student['progressAt'],
                        'level' => $leveledStudent['level'],
                        'userAuditRatio' => $leveledStudent['userAuditRatio']
                    ]);
                    // } catch (Exception) {
                    //     return $student;
                    // }
                }
            }
        }

        return $finalResult;
    }
}
