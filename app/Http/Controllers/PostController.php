<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    //

    public function index()
    {
        //$posts = Post::all(); // Shows posts created by anyone to everyone
        $posts = auth()->user()->posts()->paginate(5); // Shows posts created only by the logged in user with laravel pagination to prevent reload of list
        return view('admin.posts.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        return view('blog-post', ['post' => $post]);
    }

    public function create()
    {
        return view('admin.posts.create');
    }


    public function store()
    {
        $this->authorize('create', Post::class); // Authorize to save post

        $inputs = request()->validate([
            'title' => 'required|min:8|max:255',
            'post_image' => 'file',
            'body' => 'required'
        ]);

        if (request('post_image')) {
            $inputs['post_image'] = request('post_image')->store('images');
        }

        //$inputs['user_id'] = auth()->user()->id;
        auth()->user()->posts()->create($inputs);
        Session::flash('post-created-message', 'Post was created successfully'); //Delete message display

        return redirect()->route('post.index');
    }

    public function edit(Post $post)
    {
        $this->authorize('view', $post); // Authorize an update to only logged in account
        // Authorize an update to only logged in account using can directive inside PostController
        /*
        if(auth()->user()->can('view',$post)){
            return view('admin.posts.edit', ['post' => $post]);
        }
        */
        return view('admin.posts.edit', ['post' => $post]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // Authorize an delete
        $post->delete();

        Session::flash('message', 'Post was deleted successfully'); //Delete message display
        return back();
    }

    public function update(Post $post)
    {
        $inputs = request()->validate([
            'title' => 'required|min:8|max:255',
            'post_image' => 'file',
            'body' => 'required'
        ]);

        if (request('post_image')) {
            $inputs['post_image'] = request('post_image')->store('images');
            $post->post_image = $inputs['post_image'];
        }

        $post->title = $inputs['title'];
        $post->body = $inputs['body'];

        $this->authorize('update', $post); // Authorize an update

        $post->save();
        Session::flash('post-update-message', 'Post was updated successfully'); //Delete message display
        return redirect()->route('post.index');
    }
}
