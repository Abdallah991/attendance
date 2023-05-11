<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
// import resource to use it
use App\Http\Resources\StudentResource;
// import query service
// import requests
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
// response
use App\Traits\HttpResponses;
// using graphql
use Illuminate\Support\Facades\Http;


class StudentsController extends Controller
{

    // use custom responses
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {


        //! start of platform inegrartion
        $query = <<<GQL
     query {
         user {
        email
      firstName
      lastName
      phone: attrs(path: "Phone")
      email
      sessions {
        final_score
        updated_at
      }
         }
     }
     GQL;



        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // ! this token have to be recreated every 2 days
            // maybe a cron function will work that
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMCIsImlhdCI6MTY4MzQ2MzA0NiwiaXAiOiIxMC4xLjIwMS4xMDQsIDE3Mi4xOC4wLjIiLCJleHAiOjE2ODM4OTUwNDYsImh0dHBzOi8vaGFzdXJhLmlvL2p3dC9jbGFpbXMiOnsieC1oYXN1cmEtYWxsb3dlZC1yb2xlcyI6WyJ1c2VyIiwiYWRtaW5fcmVhZF9vbmx5Il0sIngtaGFzdXJhLWNhbXB1c2VzIjoie30iLCJ4LWhhc3VyYS1kZWZhdWx0LXJvbGUiOiJhZG1pbl9yZWFkX29ubHkiLCJ4LWhhc3VyYS11c2VyLWlkIjoiMTAiLCJ4LWhhc3VyYS10b2tlbi1pZCI6IjZmYmNjZTg1LTlmNjktNDZlYy04MWY0LWE1MGIyYWI1MTM4MCJ9fQ.N-1MvxYba7YQWwTXtUH7PCcYfqodrD_xZ7LoE0Ss-1Y'
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [

            'query' => $query
        ]);

        // var_dump($response->json()['data']['user']);

        // convert from string to array of students only
        // $filteredArray = Arr::where($response['data'], function ($value, $key) {
        //     return $value['department']['id'] == 2;
        // });
        // return response
        // TODO: create a resource for the attendnace
        return [
            "data" => $response->json()['data']['user'],
            "count" => count($response->json()['data']['user']),
            // "pages" => ceil($response->json()['count'] / 10)

        ];
        // // returning the values formated 
        // // returning the values paginated
        // // return new StudentCollection(Student::paginate());
        // // filter query code
        // $filter = new StudentFilter();
        // // dump($filter);
        // $queryItems = $filter->transform($request);
        // // if query items are null, then its like there is no condition so it will pull all the
        // $students = Student::where($queryItems);
        // // ? get the students log 
        // // TODO: Figure out a way to return the logs with the students
        // // $students = $students->with('studentLogs');
        // // return the message in success format
        // return $this->success([
        //     'students' => new StudentCollection(
        //         $students->paginate()
        //             ->appends($request->query())
        //     ),
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        //

        // Validate request
        $request->validated($request->all());
        $platformId = $request->platformId;

        //! start of platform inegrartion
        $query = <<<GQL
     query {
         user (where:{login:{_eq:$platformId}}) {
        email
      firstName
      lastName
      phone: attrs(path: "Phone")
      email
      login
      sessions {
        final_score
        updated_at
      }
         }
     }
     GQL;



        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // ! this token have to be recreated every 2 days
            // maybe a cron function will work that
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMCIsImlhdCI6MTY4MzQ2MzA0NiwiaXAiOiIxMC4xLjIwMS4xMDQsIDE3Mi4xOC4wLjIiLCJleHAiOjE2ODM4OTUwNDYsImh0dHBzOi8vaGFzdXJhLmlvL2p3dC9jbGFpbXMiOnsieC1oYXN1cmEtYWxsb3dlZC1yb2xlcyI6WyJ1c2VyIiwiYWRtaW5fcmVhZF9vbmx5Il0sIngtaGFzdXJhLWNhbXB1c2VzIjoie30iLCJ4LWhhc3VyYS1kZWZhdWx0LXJvbGUiOiJhZG1pbl9yZWFkX29ubHkiLCJ4LWhhc3VyYS11c2VyLWlkIjoiMTAiLCJ4LWhhc3VyYS10b2tlbi1pZCI6IjZmYmNjZTg1LTlmNjktNDZlYy04MWY0LWE1MGIyYWI1MTM4MCJ9fQ.N-1MvxYba7YQWwTXtUH7PCcYfqodrD_xZ7LoE0Ss-1Y'
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [

            'query' => $query
        ]);

        // var_dump($response->json()['data']['user']);

        // convert from string to array of students only
        // $filteredArray = Arr::where($response['data'], function ($value, $key) {
        //     return $value['department']['id'] == 2;
        // });
        // return response
        // TODO: create a resource for the attendnace
        // return [
        //     "data" => $response->json()['data']['user'][0],
        //     // "pages" => ceil($response->json()['count'] / 10)

        // ];
        $platformUser = $response->json()['data']['user'][0];

        // create student
        // $student = new StudentResource(Student::create($request->all()));
        $student = new StudentResource(Student::create([
            'id' => $request->id,
            'platformId' => $platformId,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $platformUser->email,
            'phone' => $platformUser->phone,
            'gender' => $platformUser->gender,
            'nationality' => $platformUser->nationality,
            'dob' => $platformUser->dob,
            'acadamicQualification' => $platformUser->acadamicQualification,
            'acadamicSpecialization' => $request->acadamicSpecialization,
            'scholarship' => 'Tamkeen',
            'supportedByTamkeen' => 'Yes',
            'fcmToken' => $request->fcmToken,
            // ! change later
            'cohortId' => 1,


        ]));
        // return new created student
        return $this->success([
            'student' => $student,
            'message' => "Student was successfull created!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);
        // return student and logs
        // $logs = StudentLog::whereBelongsTo($student)->get();
        return $this->success([
            'student' => new StudentResource(
                $student
                // ->loadMissing('studentLogs')
            ),
            // 'logs' => $logs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        // get the student 
        $student = Student::find($id);
        // update the values
        $student->update($request->all());
        // return the value of the updated student
        return $this->success([
            'student' => new $student,

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get the student
        $student = Student::find($id);
        // delete the student
        $student->delete();

        // return the value of the deleted student
        return $this->success([
            'student' => $student,
            'message' => 'The student has been deleted!'
        ]);
    }
}
