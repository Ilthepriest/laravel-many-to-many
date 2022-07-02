<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Mail\NewPostCreated;
use App\Mail\PostUpdateAdminMessage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;



class PostController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ottengo i post scritti dall'utente loggato
        //$posts = Auth::user()->posts;
        $posts = Post::where('user_id', Auth::id())->orderByDesc('id')->get();
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
        // ddd($request->all());
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

        //verifico se la richiesta contiene un file   ------> posso farlo anche cosi $request->hasFile('cover_image')
        if(array_key_exists('cover_image', $request->all())){
            //validiamo il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);
            //lo salviamo nel filesystem
            //recupero il percorso path
            $path = Storage::put('posts_images', $request->cover_image);
            //passo il percorso all'array di dati validati per il salvataggio della risorsa
            $val_data['cover_image'] = $path;
        }
        //creare istanza con dati validati
        $new_post = Post::create($val_data);
        //associo nel post i tag selezionati tramite la request dei tag
        $new_post->tags()->attach($request->tags);

        
        // return (new NewPostCreated($new_post))->render();   anteprima email da inviare

        //invia email usando istanza dell'utente nella request
        Mail::to($request->user())->send(new NewPostCreated($new_post));

        /* //invia email usando un email
        Mail::to('test@example.com')->send(new NewPostCreated($new_post)); */

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

        //verifico se la richiesta contiene un file   ------> posso farlo anche cosi $request->hasFile('cover_image')
        if(array_key_exists('cover_image', $request->all())){
            //validiamo il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);
            //cancello la cover_image che è già salvata nel db
            Storage::delete($post->cover_image);
            //lo salviamo nel filesystem
            //recupero il percorso path
            $path = Storage::put('posts_images', $request->cover_image);
            //passo il percorso all'array di dati validati per il salvataggio della risorsa
            $val_data['cover_image'] = $path;
        }

        //creare istanza con dati validati
        $post->update($val_data);

        //associo i tags ai post
        $post->tags()->sync($request->tags);

       Mail::to('bonsu@boolean.com')->send(new PostUpdateAdminMessage($post));

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
        Storage::delete($post->cover_image);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title Deleted Successfully");
    }
}
