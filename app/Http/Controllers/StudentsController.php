<?php

namespace App\Http\Controllers;

use App\Filters\StudentFilter;
use Illuminate\Http\Request;
use App\Models\Student;
// import resource to use it
use App\Http\Resources\StudentResource;
// import query service
// import requests
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentCollection;
// response
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Http;

// !
// !
// ! Remember to find a way to generate token to be used by the platforms
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
        $filter = new StudentFilter();
        // dump($filter);
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $students = Student::where($queryItems);
        // ? get the students log 
        // TODO: Figure out a way to return the logs with the students
        // $students = $students->with('studentLogs');
        // return the message in success format
        return $this->success([
            'students' => new StudentCollection(
                $students->paginate()
                    ->appends($request->query())
            ),
        ]);
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
        // Validate request through Request rules
        $request->validated($request->all());
        $platformId = $request->platformId;

        // query
        // TODO: final score
        $query = <<<GQL
     query {
      user (where:{login:{_eq:$platformId}}) {
        email
        firstName
        lastName
        login
        gender: attrs(path: "gender")
        nationality: attrs(path: "country")
        acadamicQualification:attrs(path: "howdidyou")
        dob:attrs(path: "dateOfBirth")
        phone: attrs(path: "Phone")
   
         }
     }
     GQL;



        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // ! this token have to be recreated every 2 days
            // maybe a cron function will work that
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMCIsImlhdCI6MTY4NjQ3NDcyNiwiaXAiOiIxMC4xLjIwMS40MSwgMTcyLjE4LjAuMiIsImV4cCI6MTY4NjkwNjcyNiwiaHR0cHM6Ly9oYXN1cmEuaW8vand0L2NsYWltcyI6eyJ4LWhhc3VyYS1hbGxvd2VkLXJvbGVzIjpbInVzZXIiLCJhZG1pbl9yZWFkX29ubHkiXSwieC1oYXN1cmEtY2FtcHVzZXMiOiJ7fSIsIngtaGFzdXJhLWRlZmF1bHQtcm9sZSI6ImFkbWluX3JlYWRfb25seSIsIngtaGFzdXJhLXVzZXItaWQiOiIxMCIsIngtaGFzdXJhLXRva2VuLWlkIjoiMjU3YjUwZjUtODhhZS00YmVjLWIxZDAtZmQyNTFkN2E2YjEwIn19.pU1X3zFZ7Y3n4hNNCWALqGSNRYSloT3kJKfdwPKtn_s'
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);


        // ! response if the token expired
        // TODO: call for Token when you get response
        // {
        //     "errors": [
        //         {
        //             "extensions": {
        //                 "code": "invalid-jwt",
        //                 "path": "$"
        //             },
        //             "message": "Could not verify JWT: JWTExpired"
        //         }
        //     ]
        // }

        // TODO: Call for the token and save it
        // TODO: Call again for the get the users, if didnt work response with in-vaild JWT token
        // go back to the top and call again

        // get the item from an array
        if ($response->json()['data']['user']) {
            $platformUser = $response->json()['data']['user'][0];
        } else {

            // return new created student
            return $this->error(
                ['platformId' => $platformId],
                'No student found،',
                404
            );
        }

        // create student
        $student = new StudentResource(Student::create([
            'id' => $request->id,
            'platformId' => $platformId,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $platformUser['email'],
            'phone' => $platformUser['phone'],
            'gender' => $platformUser['gender'],
            'nationality' => $platformUser['nationality'],
            'cpr' => $request->cpr,
            'dob' => date("Y-m-d H:i:s", strtotime($platformUser['dob'])),
            'acadamicQualification' => $platformUser['acadamicQualification'],
            'acadamicSpecialization' => $request->acadamicSpecialization,
            'scholarship' => 'Tamkeen',
            'supportedByTamkeen' => 'Yes',
            'fcmToken' => $request->fcmToken,
            'cohortId' => $request->cohortId,
            // TODO: change to add the socre, level


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
            ),
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
        // get the student using the id
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
