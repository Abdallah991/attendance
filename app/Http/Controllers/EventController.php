<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Student;
// import resource to use it
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;
// import query service
use App\Filters\EventFilter;
// response
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class EventController extends Controller
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
        //
        $filter = new EventFilter();
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $events = Event::where($queryItems);
        // TODO: Figure our a way to return the students with the event
        // $events = $events->with('students');
        // return the message in success format
        return $this->success([
            'events' => new EventCollection($events->paginate()->appends($request->query())),
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
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        //
        // Validate request
        $request->validated($request->all());

        // create event
        $event = new EventResource(Event::create($request->all()));

        // return new created event
        return $this->success([
            'event' => $event,
            'message' => "Event was successfully created!"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $event = Event::find($id);
        // return cohort and students
        $students = Student::whereBelongsTo($event)->get();
        return $this->success([
            'event' => new EventResource($event->loadMissing('students')),
            'students' => $students
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, $id)
    {
        //
        // get the event 
        $event = Event::find($id);
        // update the values
        $event->update($request->all());
        // return the value of the updated event
        return $this->success([
            'event' => new $event,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //
        // get the event
        $event = Event::find($id);
        // delete the event
        $event->delete();

        // return the value of the deleted event
        return $this->success([
            'event' => $event,
            'message' => 'The event has been deleted!'
        ]);
    }
}
