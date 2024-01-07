<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warrior;


class CodeWarsController extends Controller
{
    // 1- register warrior
    // Create a new warrior
    public function createWarrior(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
            'oldScore' => 'required|integer',
            'newScore' => 'required|integer',
            'platformId' => 'required|string',
            'codeWarsId' => 'required|string',
        ]);

        // Create a new warrior
        $warrior = Warrior::create($request->all());
        // return warrior
        return response()->json(['message' => 'Warrior created successfully', 'warrior' => $warrior]);
    }


    // 2- get warriors
    // Get all warriors
    public function getAllWarriors()
    {
        $warriors = Warrior::all();
        return response()->json(['warriors' => $warriors]);
    }
    // 3- create battle
    // 4- get battle

}
