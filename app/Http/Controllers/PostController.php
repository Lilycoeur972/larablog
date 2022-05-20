<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index(){

        $posts= Post:: with('category','user')->get();
        
    return view('post.index',compact('posts'));
    }

    public function create(){
     return view('post.create');
    }
}
