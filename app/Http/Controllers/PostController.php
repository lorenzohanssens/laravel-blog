<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function showCreateForm() {
        //could check if user has auth, if so return redirect to homepage but that's not the way to go about it. middleware.
        return view("create-post");
    }
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with('success', "Successfully created!");
    }

    public function viewSinglePost(Post $id) {
        //markdown
        $id['body'] = strip_tags(Str::markdown($id->body), "<a><p><ul><ol><li><strong><em><h1><h2><h3><hr><br>");
return view('single-post', ['post' => $id]);
    }
}
