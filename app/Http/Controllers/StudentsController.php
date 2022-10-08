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


class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // returning the values formated 
        // returning the values paginated
    //    return new StudentCollection(Student::paginate());

    //    filter query code
    $filter = new StudentFilter();
    $queryItems = $filter->transform($request);

    if(count($queryItems) ==0) {
        // Log::debug('Some message.');
        return new StudentCollection(Student::paginate());

    } else {
        // keep the query for pagination 
        $students = Student::where($queryItems)->paginate();
        return new StudentCollection($students->appends($request->query()));   
     }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return new StudentResource($student);
        
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
    public function update(Request $request, $id)
    {
        //
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
