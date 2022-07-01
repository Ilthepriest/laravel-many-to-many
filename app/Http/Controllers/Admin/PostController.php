<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
class PostController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        // dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //ddd($request->all());
        //validare dati
        $val_data = $request->validated();
        //generare slug
        $slug = Post::generateSlug($request->title);
        //dd($slug);
        $val_data['slug'] = $slug;
        //assegno il posto all'utente autenticato
        $val_data['user_id'] = Auth::id();
        //valido anche la category id
        $val_data['category_id'] = $request->category_id;
        //creare istanza con dati validati
        $new_post = Post::create($val_data);
        //associo nel post i tag selezionati tramite la request dei tag
        $new_post->tags()->attach($request->tags);
        //renderizziamo
        return redirect()->route('admin.posts.index')->with('message', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        // se ho troppa roba da passare con compact posso usare un array di data
       /*  $data = [
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]; */
        return view('admin.posts.edit', compact('post', 'categories', 'tags')); //$data
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
       // dd($request->all());
        //validare dati
        $val_data = $request->validated();
        //generare slug
        $slug = Post::generateSlug($request->title);
        //dd($slug);
        $val_data['slug'] = $slug;
        //creare istanza con dati validati
        $post->update($val_data);

        //associo i tags ai post
        $post->tags()->sync($request->tags);

        //renderizziamo
        return redirect()->route('admin.posts.index')->with('message', "$post->title Updated Successfully");
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title Deleted Successfully");
    }
}
