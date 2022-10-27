<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
// import resource to use it
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentCollection;
// import query service
use App\Filters\StudentFilter;
// import requests
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
// Auth


class StudentsController extends Controller
{


    public function __construct()
    {

        // only authenticated users can access these functions
        // TODO: How to presist user authentication
        // $this->middleware('auth:sanctum')->only(['create', 'update', 'edit', 'destroy', 'store','show','index']);

    }
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

        //    filter query code
        $filter = new StudentFilter();
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $students = Student::where($queryItems);
        $students = $students->with('studentLogs');
        return new StudentCollection($students->paginate()->appends($request->query()));
        //  }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        //
        return new StudentResource(Student::create($request->all()));
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
        return new StudentResource($student->loadMissing('studentLogs'));
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
