<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = Category::orderByDesc('id')->get();
        return view('admin.categories.index', compact('cats'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        //validare
        $val_data = $request->validate([
            'name' => 'required| unique:categories'
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //salvare
        Category::create($val_data);

        //redirect
        return redirect()->back()->with('message', "category $slug aggiunta con successo");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // dd($request->all());

         //validare
         $val_data = $request->validate([
            'name' => ['required', Rule::unique('categories')->ignore($category)]
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

         //salvare
         $category->update($val_data);

          //redirect
        return redirect()->back()->with('message', "category $slug modificata con successo");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('message', "category $category->name rimossa con successo");

    }
}
