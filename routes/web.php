<?php

use App\Mail\PostUpdateAdminMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();


Route::middleware('auth')->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    //Admin dashboard
    Route::get('/', 'HomeController@index')->name('dashboard');
    //Admin posts
    Route::resource('posts', 'PostController')->parameters([
        'posts' => 'post:slug'   //per mettere lo slug su post
    ]);

    Route::resource('categories', 'CategoryController')->parameters([
        'categories' => 'category:slug'   //per mettere lo slug su categories
    ])->except(['show', 'create', 'edit']);

    Route::resource('tags', 'TagController')->parameters([
        'tags' => 'tag:slug'   
    ])->except(['show', 'create', 'edit']);
    
});

/* Route::get('mailable', function(){
    $post = Post::findOrFail(1);

    return new PostUpdateAdminMessage($post);
}); */


Route::get("{any?}", function (){
    return view("guest.home");
})->where("any", ".*");