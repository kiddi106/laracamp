
@extends('layouts.main')
@section('container')

<h1 class="mb-3 text-center">{{ $title }}</h1>
<div class="row justify-content-center mb-3">
  <div class="col-md-6">
    <form action="/post">
      @if (request('category'))
      <input type="hidden" name="category" value="{{ request('category') }}">
          
      @else
      <input type="hidden" name="user" value="{{ request('user') }}">
      @endif
      <div class="input-group mb-3 ">
        <input type="text" class="form-control" placeholder="Search.." name="search" value="{{ request('search') }}" >
        <button class="btn btn-danger" type="submit" >Search</button>
      </div>
    </form>
  </div>
</div>
{{-- Jika Content post tidak kosong --}}
@if ($posts->count())

<div class="card mb-3">
    <img src="https://source.unsplash.com/1200x400/?nature,water" class="card-img-top" alt="...">
    <div class="card-body text-center">
      <h3 class="card-title"><a href="/post/{{ $posts[0]->slug }}" class="text-decoration-none text-dark" >{{ $posts[0]->title }}</a></h5>
      
      <p><small><a href="/post?user={{ $posts[0]->user->username }}" class="text-decoration-none">{{ $posts[0]->user->name }}</a> in <a href="/post?category={{ $posts[0]->category->slug }}" class="text-decoration-none">{{ $posts[0]->category->name }}</a> {{ $posts[0]->created_at->diffForHumans() }}</small></p>
    <p class="card-text"><small>{{ $posts[0]->excerpt }}</small></p>

    <a href="/post/{{ $posts[0]->slug }}" class="text-decoration-none btn btn-primary">Read More</a>
    </div>
  </div>
<div class="container">
    <div class="row">
        @foreach ($posts->skip(1) as $item)        
            <div class="col-md-4 mb-3">
                <div class="card" >
                    <div class="position-absoulute bg-dark px-3 py-2 text-white " style="background-color: rgba(0,0,0,0.7)"><a href="/post?category={{ $item->category->slug }}">{{ $item->category->name }}</a></div>
                    <img src="https://source.unsplash.com/1600x900/?{{ $item->category->name }}" class="card-img-top" alt="...">
                    <div class="card-body">
                    <h5 class="card-title">{{ $item->title }}</h5>
                    <p><small><a href="/post?user={{ $item->user->username }}" class="text-decoration-none">{{ $item->user->name }}</a> >{{ $item->category->name }}</a> {{ $posts[0]->created_at->diffForHumans() }}</small></p>
                    <p class="card-text" >{{ $item->excerpt }}</p>
                    <a href="/post/{{ $item->slug }}" class="text-decoration-none btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
    
@else
  <p class="text-center fs-4">No Post Found</p>
@endif
<div class="d-flex justify-content-end">
{{ $posts->links() }}
</div>
@endsection

