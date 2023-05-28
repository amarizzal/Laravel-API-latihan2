<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();

        // return response()->json(['data' => $posts]);

        return PostResource::collection($posts);
    }
    
    public function show($id) {
        $posts = Post::with('writer:id,username')->findOrFail($id);
        
        return new PostDetailResource($posts);
    }
    
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $request['author'] = Auth::user()->id;
        
        $posts = Post::create($request->all());
        
        return new PostDetailResource($posts->loadMissing('writer:id,username'));

    }
}
