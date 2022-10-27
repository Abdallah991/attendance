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
// sanctum
use Laravel\Sanctum\Sanctum;

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
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // success response
        // return the user & token
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API token of ' . $user->name)->plainTextToken,
        ]);
    }

    // login functionality for the users
    // returns the use as info 
    // TODO: need to manage session and token 
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
        // $request->user()->currentAccessToken()->delete();
        $user = request()->user(); //or Auth::user()
        // $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        // 
        $user->tokens()->delete();


        if ($token = $request->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            $accessToken->delete();
        }

        // TODO: check if this is neccessary anymore
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return $this->success('', "Logged out successfully");
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
        // ! we need to link the ids together
        // if (Auth::user()->id != $id) {
        //     return $this->error('', 'The user is not authorized!', 403);
        // }
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
