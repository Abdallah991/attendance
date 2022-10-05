<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    
    // controller, where the data is saved
    public function index() {
        return view("posts/index");
    }
}
