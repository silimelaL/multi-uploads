<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostImage;


use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('post_image')->orderBy('created_at', 'DESC')->get();
        return response()->json(['errpr' => false, 'data' => $posts]);
    }

    public function create()
    {
        $data = request()->validate(['title' => 'required', 'body' => 'required', 'body' => 'required']);
        auth()->user()->posts()->create($data);

        $caption = request()->validate(['post_image_caption' => 'required', 'image' => 'required']);


        $iamges = request('images');
        foreach ($iamges as $image) {
            $imagePath = $image->store('uploads', 'public');

            post()->post_image()->create([
                'post_image_caption' => $caption['post_image_caption'],
                'post_image_path' => $imagePath,
            ]);
        }

        return response()->json(['error' => false, 'data' => $data]);
    }
}
