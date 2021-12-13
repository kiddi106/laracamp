@extends('dashboard.layouts.main')

@section('container')

<div class="container">
    <div class="row my-3">
        <div class="col-lg-8">
            <h1 class="mb-3">{{ $post->title }}</h1>
            <a href="/dashboard/posts" class="btn btn-success"><span data-feather="arrow-left"></span> Back to all my project</a>
            <a href="/dashboard/posts/{{ $post->slug }}/edit" class="btn btn-warning"><span data-feather="edit"></span> Edit</a>
            <form action="/dashboard/posts/{{ $post->slug }}" class="d-inline" method="post">
                @method('delete')
                @csrf
                <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><span data-feather="x-circle"></span> Delete</button>
              </form>
            <img src="https://source.unsplash.com/1200x400/?{{ $post->category->name }}" alt="{{ $post->category->name }}" class="img-fluid mt-3">
            <article class="my-3">
                <p>{!! $post->body !!}</p>

            </article>
                
            <a href="/post" class="d-block mt-5">Back to Post</a>
                
        </div>
    </div>
</div>

@endsection