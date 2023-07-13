<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVacationRequest;
use App\Http\Requests\UpdateVacationRequest;
use App\Http\Resources\VacationCollection;
use App\Http\Resources\VacationResource;
use App\Models\Vacation;
use Illuminate\Http\Request;


use App\Traits\HttpResponses;

class VacationController extends Controller
{

    use HttpResponses;

    // get all vacations by all vacations
    public function index(Request $request)
    {
        $vacations = Vacation::all();
        // return the message in success format
        return $this->success([
            'vacations' => new VacationCollection(
                $vacations
            ),
        ]);
    }

    // store a vacation by a vacation
    public function store(StoreVacationRequest $request)
    {
        $request->validated($request->all());
        $vacation = new VacationResource(Vacation::create([
            'platformId' => $request->platformId,
            'studentId' => $request->studentId,
            'author' => $request->author,
            'description' => $request->description,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,

        ]));
        // return new created vacation
        return $this->success([
            'vacation' => $vacation,
            'message' => "Vacation was successfull created!"
        ]);
    }

    // show a student's of a vacation
    public function show($id)
    {
        $vacations = Vacation::where('studentId', $id)->get();
        return $this->success([
            'vacations' => new VacationCollection(
                $vacations
            ),
        ]);
    }

    // update a student's of a vacation
    public function update(UpdateVacationRequest $request, $id)
    {
        // get the vacation using the id
        $vacation = Vacation::find($id);
        // update the values
        $vacation->update($request->all());
        // return the value of the updated vacation
        return $this->success([
            'vacation' =>  $vacation,
        ]);
    }

    // delete student's vacation
    public function destroy($id)
    {
        // get the vacation
        $vacation = Vacation::find($id);
        // delete the vacation
        $vacation->delete();
        // return the value of the deleted student
        return $this->success([
            'vacation' => $vacation,
            'message' => 'The student has been deleted!'
        ]);
    }
}
