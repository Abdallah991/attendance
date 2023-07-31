<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;





//! this Controller requires an emp_code to get the transctions for a specific bio time user
//? get attendance for a specific user
class RoleController extends Controller
{

    // TODO: get all attendnace instances
    public function index(Request $request)
    {
        $roles = Role::all();
        return $roles;
        return $this->success([
            'roles' => new RoleCollection(
                $roles
            ),
            'user' => Auth::user()
        ]);
    }


    // TODO: Create User attendance
    public function create()
    {
    }
    public function store(Request $request)
    {
    }


    // ? get Attendance for specific user
    public function show($id)
    {
    }



    // ? is it possible to edit a certain attendance 
    // ! No 
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
    }


    // Delete a certain transaction
    public function destroy($id)
    {
    }
}
