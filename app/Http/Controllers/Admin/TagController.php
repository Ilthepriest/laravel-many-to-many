<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        //validare
        $val_data = $request->validate([
            'name' => 'required| unique:tags'
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //salvare
        Tag::create($val_data);

        //redirect
        return redirect()->back()->with('message', "tag $slug aggiunta con successo");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        // dd($request->all());

         //validare
         $val_data = $request->validate([
            'name' => ['required', Rule::unique('tags')->ignore($tag)]
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

         //salvare
         $tag->update($val_data);

          //redirect
        return redirect()->back()->with('message', "tag $slug modificata con successo");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('message', "tag $tag->name rimossa con successo");

    }
}
