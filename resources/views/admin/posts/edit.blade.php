@extends('layouts.admin')

@section('content')
<h2>Edit {{$post->title}}</h2>
@include('partials.errors')
<form action="{{route('admin.posts.update', $post->slug)}}" method="post">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Inserisci titolo" aria-describedby="titleHelper" value="{{old('title', $post->title)}}">
      <small id="titleHelper" class="text-muted">Inserisci il testo</small>
    </div>

    <div class="mb-3">
        <div class="media">
            <img width="150" src="{{$post->cover_image}}" alt="{{$post->title}}">
        </div>
      <label for="cover_image" class="form-label">cover_image</label>
      <input type="text" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" placeholder="Inserisci titolo" aria-describedby="cover_imageHelper" value="{{old('cover_image', $post->cover_image)}}">
      <small id="cover_imageHelper" class="text-muted">Inserisci il testo</small>
    </div>

    <div class="mb-3">
      <label for="category_id" class="form-label">Categorie</label>
      <select class="form-control form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
      <option value="">Seleziona una categoria</option>
        @foreach($categories as $category)
        <!-- risolto bug in edit non andava se un post non aveva una categoria -->
        <option value="{{$category->id}}" {{ $category->id == old('category_id', $post->category ? $post->category->id : '' ) ? 'selected' : ''}} >{{$category->name}}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="tags" class="form-label">Tags</label>
      <select multiple class="form-select" name="tags[]" id="tags" aria-label="tags"> <!-- parentesi quadre in name perché ho una lista di tags -->
        <option value="" disabled>Select tags</option>
        @forelse ($tags as $tag)
        @if($errors->any())
          <option value="{{$tag->id}}" {{ in_array($tag->id, old('tags') ) ? 'selected' : '' }}>{{$tag->name}}</option>
        @else
          <option value="{{$tag->id}}" {{ $post->tags->contains($tag->id) ? 'selected' : ''}}>{{$tag->name}}</option>
          
        @endif
        @empty
        <option value="">No tags</option>
        @endforelse
      </select>
    </div>

   <div class="mb-3">
     <label for="content" class="form-label">Content</label>
     <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" rows="4">
     {{old('content', $post->content)}}
     </textarea>
   </div>

   <button type="submit" class="btn btn-primary">Update post</button>
</form>

@endsection