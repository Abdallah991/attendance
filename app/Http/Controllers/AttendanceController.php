<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


//! this Controller requires an emp_code to get the transctions for a specific bio time user
//? get attendance for a specific user
class AttendanceController extends Controller
{
    // ? get Attendance for specific user
    public function getAttendance($id)
    {
        //TODO: add support for start time and end time
        // you can define the response max size
        $pageSize = 1000;
        $apiToken =  config('app.ATTENDANCE_TOKEN');

        // return $apiToken;

        // get all transactions for a specific student and admin and staff
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . '48e111ccd207225fff4b28cc5f7e6d68acf6b479'
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
}
