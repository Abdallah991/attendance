<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
use App\Http\Requests\LoginUserRequest;
// Auth
use Illuminate\Support\Facades\Auth;
// trait
use App\Traits\HttpResponses;

class UserController extends Controller
{
    // use custom responses
    use HttpResponses;


    // register functionality and creating a user and their authenitication info
    function register(StoreUserRequest $request)
    {

        // Validate request
        $request->validated($request->all());

        // create user
        $user = new UserResource(User::create($request->all()));

        // $user = User::create([
        //     'firstName' => $request->firstName,
        //     'lastName' => $request->lastName,
        //     'position' => $request->position,
        //     'joinDate' => $request->joinDate,
        //     'gender' => $request->gender,
        //     'dob' => $request->dob,
        //     'phone' => $request->phone,
        //     'fcmToken' => $request->fcmToken,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'permission' => $request->permission,


        // ]);

        // success response
        // return the user & token
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API token of ' . $user->name)->plainTextToken,
        ]);
    }

    // login functionality for the users
    // returns the use as info 
    // TODO: need to tie this functionality to the guard in the front end
    function login(LoginUserRequest $request)
    {

        // validate request
        $request->validated($request->all());


        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentails do not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        $request->session()->regenerate();
        return $this->success([
            'users' => $user,
            'token' => $user->createToken('Api token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();
        // TODO: Figure out a way to destroy the token
        // $request->user()->currentAccessToken()->delete();

        // Auth::user()->tokens->each(function ($token, $key) {
        //     $token->delete();
        // });



        return $this->success('', "Logged out successfully, Your token have been deleted!");
    }



    // Get all users implemnetation
    public function index(Request $request)
    {

        $filter = new UserFilter();
        $queryItems = $filter->transform($request);
        // if query items are null, then its like there is no condition so it will pull all the
        $users = User::where($queryItems);

        // return users and paginate through query
        return $this->success([
            'users' => new UserCollection($users->paginate()->appends($request->query())),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //return the created user
        return $this->success([
            'user' => new  UserResource(User::create($request->all())),
            'message' => "User was successfull created!"
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

        $user = User::find($id);
        // return the quered user
        return $this->success([
            'user' => new UserResource($user),

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
    public function update(UpdateUserRequest $request, $id)
    {
        // get the student 
        $user = User::find($id);
        // update the values
        $user->update($request->all());
        // return the updated user 
        // TODO: make sure if no is needed or not
        return $this->success([
            'user' => new $user,

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
        // get the user
        $user = User::find($id);
        // delete the user
        $user->delete();

        // return the deleted user
        return $this->success([
            'user' => $user,
            'message' => 'The user has been deleted!'
        ]);
    }
}
