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
use App\Http\Resources\StudentCollection;
// response
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;



class StudentsController extends Controller
{

    // use custom responses
    use HttpResponses;
    // get all the students
    // response will get the user signed in data
    public function index(Request $request)
    {
        $students = Student::all();
        // response will get the user signed in data
        return $this->success([
            'students' => new StudentCollection(
                $students
            ),
            'user' => Auth::user()
        ]);
    }


    // store the value of the student
    // ! dob causes problems if before 1970 because of sql datatype
    public function store(StoreStudentRequest $request)
    {
        // Validate request through Request rules
        $request->validated($request->all());
        $platformId = $request->platformId;
        // Token expires every 3 days
        $apiToken =  config('app.GRAPHQL_TOKEN');
        // * adding gender and genders and phone and phoneNumber to avoid null exception writing to the database
        $query = <<<GQL
     query {
      user (where:{login:{_eq:$platformId}}) {
        email
        firstName
        lastName
        login
        gender: attrs(path: "gender")
        genders: attrs(path: "genders")
        nationality: attrs(path: "country")
        acadamicQualification:attrs(path: "howdidyou")
        dob:attrs(path: "dateOfBirth")
        phone: attrs(path: "Phone")
        phoneNumber: attrs(path: "PhoneNumber")

   
         }
     }
     GQL;
        //  API call to the platform with QL query 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
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

        // get the item from an array
        if ($response->json()['data']['user']) {
            $platformUser = $response->json()['data']['user'][0];
        } else {
            // return error if the student not found
            return $this->error(
                ['platformId' => $platformId],
                'No student found،',
                404
            );
        }

        // create student
        // date of birth 
        $student = new StudentResource(Student::create([
            'id' => $request->id,
            'platformId' => $platformId,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $platformUser['email'],
            'phone' => $platformUser['phone'] ?? $platformUser['phoneNumber'] ?? '',
            'gender' => $platformUser['gender'] ?? $platformUser['genders'] ?? 'NA',
            'nationality' => $platformUser['nationality'],
            'cpr' => $request->cpr,
            'dob' => date("Y-m-d H:i:s", strtotime($platformUser['dob'])),
            'acadamicQualification' => $platformUser['acadamicQualification'] == null ? 'placeholder' : $platformUser['acadamicQualification'],
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

    // call a student
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



    public function edit($id)
    {
        //
    }

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


    // delete student
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

    // get birthdays of students
    public function birthdays(Request $request)
    {
        // get all students
        $students = Student::all();
        $birthdays = [];
        // loop ofver students and push names and birthdays
        foreach ($students as $student) {

            array_push($birthdays, [
                'name' => $student['firstName'] . ' ' . $student['lastName'],
                'date' => $student['dob'],
            ]);
        }
        // return birthdays
        return $this->success([
            'birthdays' =>
            $birthdays,
        ]);
    }


    // sync students data 
    // * sync students data
    public function syncStudents(Request $request)
    {
        // get all students from sis database
        $systemStudents = Student::all();
        // get the students from 01 database
        $apiToken =  config('app.GRAPHQL_TOKEN');
        // * adding gender and genders and phone and phoneNumber to avoid null exception writing to the database
        $query = <<<GQL
      query {
       event(where:{registrationId:{_eq:23}}) {
        users {
            email: attrs(path: "email")
            firstName: attrs(path: "firstName")
            lastName: attrs(path: "lastName")
            login
         gender: attrs(path: "gender")
         genders: attrs(path: "genders")
         nationality: attrs(path: "country")
         acadamicQualification:attrs(path: "howdidyou")
         dob:attrs(path: "dateOfBirth")
         phone: attrs(path: "Phone")
         phoneNumber: attrs(path: "PhoneNumber")
        }
        }
        }
      GQL;
        //  API call to the platform with QL query 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);



        $platformStudents = $response['data']['event'][0]['users'];
        // return [
        //     'platformStudents' => $platformStudents,
        //     'system students' => $systemStudents
        // ];
        foreach ($platformStudents as $platformStudent) {
            // return $platformStudent;
            // return $platformStudent['login'];
            $existingStudent = Student::where('platformId', $platformStudent['login'])->first();

            if ($existingStudent) {
                //* uncomment unnecessary updates.
                // Update the existing student's fields with data from the API response
                //    $existingStudent->firstName = $platformStudent['firstName'];
                //    $existingStudent->lastName = $platformStudent['lastName'];
                //    $existingStudent->email = $platformStudent['email'];
                //    $existingStudent->phone = $platformStudent['candidate']['phone'] ?? $platformStudent['candidate']['phoneNumber'] ?? '';
                $existingStudent->gender = $platformStudent['gender'] ?? $platformStudent['genders'] ?? null;
                $existingStudent->nationality = $platformStudent['nationality'] ?? null;

                // Parse the date of birth from the API response into a DateTime object and format it to the 'YYYY-MM-DD' format
                $dob = $platformStudent['dob'] ? new DateTime(substr($platformStudent['dob'], 0, 10)) : null;
                $dobStr = $dob ? $dob->format('Y-m-d') : null;
                $existingStudent->dob = $dobStr;
                // $existingStudent->acadamicQualification = $platformStudent['acadamicQualification'] ?? null;
                // $existingStudent->acadamicSpecialization = $platformStudent['acadamicSpecialization'] ?? null;
                //* uncomment unnecessary updates.
                // $existingStudent->employment = $platformStudent['candidate']['employment'] ? $platformStudent['candidate']['employment'] : 'unknown';
                // $existingStudent->howDidYouHear = $platformStudent['candidate']['howDidYouHear'] ? $platformStudent['candidate']['howDidYouHear'] : 'unknown';
                // $existingStudent->progresses = json_encode($platformStudent['candidate']['progresses']);
                // $existingStudent->registrations = json_encode($platformStudent['candidate']['registrations']);
                // Save the updated Applicant model to the database
                $existingStudent->save();
            }
        }

        return Student::all();
    }
}
