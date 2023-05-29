<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    public function index() {
        $posts = Post::all();

        // return response()->json(['data' => $posts]);

        return PostDetailResource::collection($posts->loadMissing(['writer', 'comments']));
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

        if($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();

            $path = Storage::putFileAs('image', $request->file, $fileName.'.'.$extension);
            $request['image'] = $fileName.'.'.$extension;
        }

        $request['author'] = Auth::user()->id;
        
        $posts = Post::create($request->all());
        
        return new PostDetailResource($posts->loadMissing('writer:id,username'));

    }
    
    public function update(Request $request) {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($request->id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }
    
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();
        
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
