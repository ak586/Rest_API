<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware(['role:admin|writer'])->only(['update', 'destroy', 'store']);

    }
     

    public function index()
    {
        
        $post = Post::all();
        return response()->json($post,200);
        // Adding permissions to a user
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
        $post = Post::create($validate);
        return response()->json($post,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post=Post::find($id);
        return response()->json([$post],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $post = Post::find($id);
        if(auth()->user()->hasRole('writer') && auth()->user()->id!==$post->author->id){
            return response()->json(['message' => 'You do not have permissions to edit this post'], 403);
        }
            $validate = $request->validate( [
                'title' => 'sometimes',
                'body' => 'sometimes',
            ]);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return response()->json($post,200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if(auth()->user()->hasRole('writer') && auth()->user()->id!==$post->author->id){
            return response()->json(['message' => 'You do not have permissions to edit this post'], 403);
        }
        $post->delete();
        return response()->json(['message'=>'post deleted', 'post'=>$post],200);  
    }
}
