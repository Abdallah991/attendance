<?php

namespace App\Http\Controllers;

// TODO: search api
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\SP;
use App\Models\Comment;




class SearchController extends Controller
{

    //? search student
    public function searchStudents(Request $request)
    {

        $filteredArray = [];
        $searchValue = $request->searchValue;
        $students = Student::all();


        for ($i = 0; $i < count($students); $i++) {

            // making sure that our search considers capital and lowercase characters
            // search on the
            // 1- email
            // 2- first name 
            // 3- last name 
            // 4- cpr
            // 5- platformId
            if (
                str_contains(strtolower($students[$i]['email']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['firstName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['firstName']) . ' ' . strtolower($students[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['cpr']), strtolower($searchValue)) ||
                str_contains(strtolower($students[$i]['platformId']), strtolower($searchValue))
            ) {

                array_push($filteredArray, $students[$i]);
            }
        }

        return $filteredArray;
    }

    public function searchApplicants(Request $request)
    {
        $filteredArray = [];
        $searchValue = $request->searchValue;
        $spApplicants = SP::all();
        for ($i = 0; $i < count($spApplicants); $i++) {
            // making sure that our search considers capital and lowercase characters
            // search on the
            // 1- email
            // 2- first name 
            // 3- last name 
            // 4- platformId
            if (
                str_contains(strtolower($spApplicants[$i]['email']), strtolower($searchValue)) ||
                str_contains(strtolower($spApplicants[$i]['firstName']), strtolower($searchValue)) ||
                str_contains(strtolower($spApplicants[$i]['firstName']) . ' ' . strtolower($spApplicants[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($spApplicants[$i]['lastName']), strtolower($searchValue)) ||
                str_contains(strtolower($spApplicants[$i]['platformId']), strtolower($searchValue))
            ) {
                $comments = Comment::where('platformId', $spApplicants[$i]['platformId'])->get();
                if (count($comments)) {
                    $spApplicants[$i]['comments'] = $comments;
                } else {
                    $spApplicants[$i]['comments'] = [];
                }

                array_push($filteredArray, $spApplicants[$i]);
            }
        }

        return $filteredArray;
    }
}
