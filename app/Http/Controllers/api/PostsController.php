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

    public function __construct()
    {
        $this->middleware(['role:admin|Super-Admin'])->only(['destroy', 'store', 'update']);
    }

    public function index()
    {
        $post = Post::all();
        return response()->json(["posts" => $post], 200);
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
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]);
        return response()->json($post, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return response()->json([$post], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $post = Post::find($id);
        $user = auth()->user();
        if (!$user->hasRole('Super-Admin') && auth()->user()->id != $post->author->id) {
            return response()->json(["message" => "You can't edit this post."], 403);
        }
        $validate = $request->validate([
            'title' => 'sometimes|required',
            'body' => 'sometimes|required',
        ]);
        if ($request->title)
            $post->title = $request->title;
        if ($request->body)
            $post->body = $request->body;
        $post->save();
        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        $user = auth()->user();
        if (!$user->hasRole('Super-Admin') && auth()->user()->id != $post->author->id) {
            return response()->json(["message" => "You don't have permission to delete this post."], 403);
        }
        $post->delete();
        return response()->json(['message' => 'post deleted', 'post' => $post], 200);
    }
}
