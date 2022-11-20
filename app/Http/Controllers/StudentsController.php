<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentLog;
// import resource to use it
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;
// import query service
use App\Filters\StudentFilter;
// import requests
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
// response
use App\Traits\HttpResponses;



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
        // returning the values formated 
        // returning the values paginated
        // return new StudentCollection(Student::paginate());
        // filter query code
        $filter = new StudentFilter();
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $students = Student::where($queryItems);
        // ? get the students log 
        // TODO: Figure out a way to return the logs with the students
        $students = $students->with('studentLogs');
        // return the message in success format
        return $this->success([
            'students' => new StudentCollection($students->paginate()->appends($request->query())),
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
        //

        // Validate request
        $request->validated($request->all());

        // create student
        $student = Student::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'nationality' => $request->nationality,
            'supportedByTamkeen' => $request->supportedByTamkeen,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'fcmToken' => $request->fcmToken,
            'email' => $request->email,
            'cohortId' => $request->cohortId,


        ]);
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
        $logs = StudentLog::whereBelongsTo($student)->get();
        return $this->success([
            'students' => new StudentResource($student->loadMissing('studentLogs')),
            'logs' => $logs
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
