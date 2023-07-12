<?php

namespace App\Http\Controllers;

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
                            // 'skill_go' => 0,
                            // 'skill_algo' => 0,
                            'lastProjectGaveAuditTo' => $transaction['object']['name'],
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
                            // 'skill_go' => 0,
                            // 'skill_algo' => 0,
                            'progressAt' => $transaction['object']['name'],
                            'auditDate' => explode("T", $transaction['createdAt'])[0]

                        ]
                    );

                    break;
            }
        }

        // TODO: Use better algorithm
        foreach ($studentsArray as $student) {
            $match = false;
            foreach ($result as $activeStudent) {
                if ($student['user']['login'] == $activeStudent['login']) {
                    $match = true;
                }
            }
            if (!$match) {
                array_push($result, $student['user']);
            }
        }

        return $result;
    }
}
