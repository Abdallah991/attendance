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
// import requests
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
// Auth
use Illuminate\Support\Facades\Auth;
// trait
use App\Traits\HttpResponses;
class UserController extends Controller
{
    use HttpResponses;

  
    // register functionality and creating a user and their authenitication info
function register(StoreUserRequest $request) {

    // Validate request
    $request->validated($request->all());

    // create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
   
// success response
// return the user & token
    return $this->success([
        'user' => $user,
        'token' => $user->createToken('API token of '. $user->name)->plainTextToken,
    ]);
}

// login functionality for the users
// returns the use as info 
// TODO: need to manage session and token 
// TODO: need to tie this functionality to the guard in the front end
function login(Request $request) {


    // if(!$user || !Hash::check($request->password, $user->password)) {

    //     return ["ERROR", "Email or Password are not matched"];
    // }

    // return $user;

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

     $user = User::where('email',$request->email)->first();
     $request->session()->regenerate();
     return $user;

}

public function logout(Request $request)
{
  Auth::logout();
  $request->session()->invalidate();
  $request->session()->regenerateToken();
  return "Logged out successfully";
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
    public function store(StoreUserRequest $request)
    {
        //
        return new UserResource(User::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return new UserResource($user);
        
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
    public function update(UpdateUserRequest $request, $id)
    {
        // get the student 
        $user = User::find($id);
        // update the values
        $user->update($request->all());
    }


}
