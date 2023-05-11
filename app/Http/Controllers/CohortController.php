<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCohortRequest;
use App\Http\Requests\UpdateCohortRequest;
use App\Models\Cohort;
use App\Models\Student;
// import resource to use it
use App\Http\Resources\CohortResource;
use App\Http\Resources\CohortCollection;
// import query service
use App\Filters\CohortFilter;
// response
use App\Traits\HttpResponses;
use Illuminate\Http\Request;



class CohortController extends Controller
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
        $filter = new CohortFilter();
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $cohorts = Cohort::where($queryItems);
        // TODO: Figure our a way to return the students with the cohort
        // $cohorts = $cohorts->with('students');
        // return the message in success format
        return $this->success([
            'cohorts' => new CohortCollection($cohorts->paginate()->appends($request->query())),
        ]);
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
     * @param  \App\Http\Requests\StoreCohortRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCohortRequest $request)
    {
        //
        // Validate request
        $request->validated($request->all());

        // create cohort
        $cohort = Cohort::create([
            'name' => $request->name,
            'year' => $request->year,
            'school' => $request->school,



        ]);
        // return new created cohort
        return $this->success([
            'cohort' => $cohort,
            'message' => "Cohort was successfull created!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cohort = Cohort::find($id);
        // return cohort and students
        $students = Student::whereBelongsTo($cohort)->get();
        return $this->success([
            'cohort' => new CohortResource($cohort->loadMissing('students')),
            'students' => $students
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function edit(Cohort $cohort)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCohortRequest  $request
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCohortRequest $request, $id)
    {
        // get the cohort 
        $cohort = Cohort::find($id);
        // update the values
        $cohort->update($request->all());
        // return the value of the updated cohort
        return $this->success([
            'cohort' => new $cohort,

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cohort  $cohort
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // get the cohort
        $cohort = Cohort::find($id);
        // delete the cohort
        $cohort->delete();

        // return the value of the deleted cohort
        return $this->success([
            'cohort' => $cohort,
            'message' => 'The cohort has been deleted!'
        ]);
    }
}
