<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Http;

class StatisticsController extends Controller
{

    // ? Get Students Transactions and Activity
    public function studentsProgress()
    {
        // platform token to get the API Token from .env
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

        //  graph api call for students in cohort
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
        // get the data in json object
        $firstProjectArray = $ProjectResponse->json()['data']['transaction'];
        $studentsArray = $students->json()['data']['event'][0]['usersRelation'];
        // add all the students relevant data
        $students = array();
        foreach ($studentsArray as $student) {
            array_push($students, [
                'login' => $student['user']['login'],
                'firstName' => $student['user']['firstName'],
                'lastName' => $student['user']['lastName'],
                'level' => $student['level'],
                'userAuditRatio' => $student['userAuditRatio'],
                'up' => false,
                'down' => false,
                'auditDate' => false,
                'auditGivenDate' => false,
                'auditReceivedDate' => false,
                'progressAt' => '-'
            ]);
        }

        // ? optimize rather than using for loop
        // ? time complexity is O(n * m)
        // ! this is a part of syncing the students progress
        foreach ($firstProjectArray as $transaction) {
            foreach ($students as $key => $student) {
                if ($student['login'] === $transaction['user']['login']) {
                    // transaction types up, down
                    // lastProjectGaveAuditTo, progressAt
                    // audit given date
                    // audit recieved date
                    $date = explode("T", $transaction['createdAt'])[0];
                    if (!$student['up'] && $transaction['type'] == "up") {
                        $students[$key]['up'] = 1;
                        // 1- we figure out the lastProject gave audit to through the time
                        $students[$key]['lastProjectGaveAuditTo'] = $transaction['object']['name'];
                        $students[$key]['auditGivenDate'] = $date;
                    } else if ($transaction['type'] == "up") {
                        $students[$key]['up']++;
                        if ($students[$key]['auditGivenDate'] < $date) {
                            $students[$key]['auditGivenDate'] = $date;
                            $students[$key]['lastProjectGaveAuditTo'] = $transaction['object']['name'];
                        }
                    }

                    if (!$student['down'] && $transaction['type'] == "down") {
                        $students[$key]['down'] = 1;
                        $students[$key]['progressAt'] = $transaction['object']['name'];
                        $students[$key]['auditReceivedDate'] = $date;
                    } else if ($transaction['type'] == "down") {
                        $students[$key]['down']++;
                        if ($students[$key]['auditReceivedDate'] < $date) {
                            $students[$key]['auditReceivedDate'] = $date;
                            $students[$key]['progressAt'] = $transaction['object']['name'];
                        }
                    }

                    $students[$key]['auditDate'] = $students[$key]['auditReceivedDate'] < $students[$key]['auditGivenDate'] ? $students[$key]['auditGivenDate'] : $students[$key]['auditReceivedDate'];
                }
            }
        }

        // * update each student with their project 
        foreach ($students as $key => $student) {
            // loook up the student
            $existingStudent = Student::where('platformId', $student['login'])->first();
            // if student exists
            if ($existingStudent) {
                // update information
                $existingStudent->progressAt = $student['progressAt'];
                $existingStudent->AuditGiven = $student['up'];
                $existingStudent->AuditReceived = $student['down'];
                if ($student['down']) {
                    $existingStudent->userAuditRatio = $student['userAuditRatio'];
                }
                $existingStudent->level = $student['level'];
                $existingStudent->auditDate = $student['auditDate'];
                $existingStudent->auditReceivedDate = $student['auditReceivedDate'];
                $existingStudent->auditGivenDate = $student['auditGivenDate'];
                // save the student
                $existingStudent->save();
            }
        }

        return $students;
    }
}
