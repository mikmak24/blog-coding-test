<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReply;
use Log;


class BlogController extends Controller
{
    public function index(){
        $posts = Post::with('comments.replies')->get();
        return view('welcome', ['posts' => $posts]);

    }

    public function store(Request $request){

        Comment::create([
            'post_id' => $request->post_id,
            'user_name' => $request->name,
            'comment' => $request->comment,
        ]);

    }

    public function storereply(Request $request){   
        
        $count = CommentReply::where('comment_id', (int)$request->comment_id)->count();

        if($count <= 2){

        CommentReply::create([
            'comment_id' => (int)$request->comment_id,
            'user_name' => 'markp',
            'comment_reply' => $request->reply,
        ]);

        } else {
            return 'exceed';
        }

    }
}
