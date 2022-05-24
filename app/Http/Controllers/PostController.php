<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $posts = Post::with('category','user')->get();
        
          return view('post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(); 

        return view('post.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {

        $imageName = $request->image->store('posts');
        Post::create([
            'title'=> $request->title,
            'content'=> $request->content,
            'image'=> $imageName
        ]);
        return redirect()->route('dashboard')->with('succes','Votre post a été crée.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)  
    {
        if (Gate::denies('udapte-post',$post)) {
            abort(403);
        }
        $categories = Category::all(); 

        return view('post.edit',compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    
    public function update(StorePostRequest $request, Post $post)
    {
        $arrayUdapte=([
            'title'=>$request->title,
            'content'=>$request->content
        ]);

        if($request->image= null){
            $imageName= $request->image->store('posts');

            $arrayUdapte=array_merge($arrayUdapte,[
                'image'=> $imageName
            ]);
            
        }
        

        $post->update($arrayUdapte);
        return redirect()->route('dashboard')->with('success','votre post a été modifié');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */

    public function destroy(Post $post)
    {
        if (Gate::denies('destroy-post',$post)) {
            abort(403);
        }
            $post->delete();

            return redirect()->route('dashboard')->with('success','votre post a été suprrimé');
    }
}