<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    //
    public function index() {
        //? return the value of index
        // return view('students/index');
        // ? query builder 
        $id =2 ;
        // ? query call #1
        // $students = DB::select('select * from students WHERE id=?', [$id]);
        // ? query call #2
        // $students = DB::table('students')
        //     ->where('id', $id)
        //     -> get();
        // ? query call #3
        // $students = DB::table('students')
        //     ->select('body')
        //     -> get();
        // ? query number 4 
        // $students = DB::table('students')
        //     ->where('created_at','<', now()-> subday())
        //     ->orwhere('name', 'Donya adel')
        //     -> get();
        // ? query number 5
        // $students = DB::table('students')
        // ->whereBetween('id',[1,3])
        // -> get();
         // ? query number 6
        //  $students = DB::table('students')
        //  ->whereNull('created_at')
        //  -> get();
          // ? query number 7
        //   $students = DB::table('students')
        //   ->find($id);

           // ? query number 8
           $students = DB::table('students')
           ->count();
        //    you can also insert, update and delete rows
 

         
        dd($students);
    }
}
