<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentLog;
use App\Http\Resources\StudentLogResource;
use App\Http\Resources\StudentLogCollection;
use App\Filters\StudentLogFilter;


class StudentLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // provides links which include API calls for 
        // 1- next page
        // 2- last page
        // 3- previous page
        // 4- first page
        $filter = new StudentLogFilter();
        $queryItems = $filter->transform($request);
        if(count($queryItems) ==0) {
            // return normal response
            return new StudentLogCollection(StudentLog::paginate());
        } else {
            // return query response
            // to account for pagination and keep the filters
            $studentLogs = StudentLog::where($queryItems)->paginate();
            return new StudentLogCollection($studentLogs->appends($request->query()));


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
        //show a student log
        $studentLog = StudentLog::find($id);
        return new StudentLogResource($studentLog);
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
