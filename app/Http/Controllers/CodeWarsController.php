<?php

namespace App\Http\Controllers;

use App\Models\Battle;
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
        // check if warrior exsist
        $existingWarrior = Warrior::where('codeWarsId', $request->codeWarsId)->first();
        if ($existingWarrior) {
            return;
        }

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
    // Create a new battle
    public function createBattle(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
        ]);
        $existingBattle = Battle::where('name', $request->name)->first();
        if ($existingBattle) {
            return;
        }
        // Create a new battle
        $battle = Battle::create($request->all());

        return response()->json(['message' => 'Battle created successfully', 'battle' => $battle]);
    }
    // 4- get all battles
    // Get all battles
    public function getAllBattles()
    {
        $battles = Battle::with('warriors')->get();
        return response()->json(['battles' => $battles]);
    }

    // 5- get specific battle
    // Get a specific battle by ID
    public function getBattle($id)
    {
        $battle = Battle::with('warriors')->find($id);

        if (!$battle) {
            return response()->json(['message' => 'Battle not found'], 404);
        }

        return response()->json(['battle' => $battle]);
    }

    // 6-edit battle
    public function editBattle(Request $request, $id)
    {
        $battle = Battle::find($id);

        if (!$battle) {
            return response()->json(['message' => 'Battle not found'], 404);
        }

        // Validate the request data
        $request->validate([
            'winner' => 'sometimes|string',
        ]);

        // Update the battle data
        // ! check if both needed
        $battle->winner = $request->input('winner');
        $battle->save();

        return response()->json(['message' => 'Battle updated successfully', 'battle' => $battle]);
    }

    // 7- add warriors to a battle 
    public function addWarriorsToBattle(Request $request)
    {
        $battle = Battle::find($request->id);

        if (!$battle) {
            return response()->json(['message' => 'Battle not found'], 404);
        }

        // Get an array of warrior IDs from the request
        $warriorIds = $request->input('warriorIds');

        // Attach warriors to the battle
        $battle->warriors()->attach($warriorIds);
        $warriors = $battle->warriors;


        return response()->json([
            'message' => 'Warriors added to the battle',
            'battle' => $battle,
            'warriors' => $warriors
        ]);
    }

    // 8- update code wars id
    public function updateOldScores(Request $request)
    {
        // Validate the request data
        $request->validate([
            '*.codeWarsId' => 'required|string|exists:warriors,codeWarsId',
            '*.score' => 'required|integer',
        ]);

        // Get the data from the request
        $data = $request->all();
        // Loop through the data and update old scores
        foreach ($data as $item) {
            $warrior = Warrior::where('codeWarsId', $item['codeWarsId'])->first();

            if ($warrior) {
                $warrior->oldScore = $item['score'];
                $warrior->save();
            }
        }

        return response()->json(['message' => 'Old scores updated successfully']);
    }
}
