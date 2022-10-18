<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
// import model 
use App\Models\User;
// import Filters
use App\Filters\UserFilter;
// import resources
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;



class UserController extends Controller
{
    
  
    // register functionality and creating a user and their authenitication info
function register(Request $request) {

    $user = new User;
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = Hash::make($request->input('password'));
    $user->save();
    return $user;
}

// login functionality for the users
// returns the use as info 
// TODO: need to manage session and token 
// TODO: need to tie this functionality to the guard in the front end
function login(Request $request) {

    $user = User::where('email',$request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password)) {

        return ["ERROR", "Email or Password are not matched"];
    }

    return $user;

}



// Get all users implemnetation
    public function index(Request $request)
    {
    
        $filter = new UserFilter();
        $queryItems = $filter->transform($request);
    // if query items are null, then its like there is no condition so it will pull all the
        $users = User::where($queryItems);
        // $students = $students->with('studentLogs');
        return new UserCollection($users->paginate()->appends($request->query()));   
    //  }

    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // // public function create()
    // // {
    // //     //
    // // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreStudentRequest $request)
    // {
    //     //
    //     return new StudentResource(Student::create($request->all()));
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     $student = Student::find($id);
    //     return new StudentResource($student->loadMissing('studentLogs'));
        
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(UpdateStudentRequest $request, $id)
    // {
    //     // get the student 
    //     $student = Student::find($id);
    //     // update the values
    //     $student->update($request->all());
    // }


}
