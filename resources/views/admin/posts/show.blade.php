@extends('layouts.admin')

@section('content')

<div class="posts d-flex px-4">
    <div class="cover_image">
        <img width="400" class="img-fluid" src="{{asset('storage/' . $post->cover_image)}}" alt="{{$post->title}}">
    </div>
    <div class="post-data px-4">
        <h1>{{$post->title}}</h1>
        <div class="metadata">
            <div class="category">
                <strong>Category:</strong>
                Category: {{$post->category ? $post->category->name : 'Uncategorized'}}
            </div>
            <div class="tags">
                <strong>Tags:</strong>
                @if (count($post->tags) > 0)  <!-- se il post ha un tag maggiore di 0 lo mostriamo -->
                    @foreach ($post->tags as $tag)
                        <span>#{{$tag->name}}</span>
                    @endforeach
                @else
                <span>N/A</span>
                @endif
            </div>
            
            
        </div>
        <div class="content">
            {{$post->content}}
        </div>
    </div>
</div>



@endsection