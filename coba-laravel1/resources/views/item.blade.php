@extends('layouts.main')
@section('container')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <h1 class="mb-3">{{ $post->title }}</h1>
            <p><a href="/authors/{{ $post->user->username }}" class="text-decoration-none" >by: {{ $post->user->name }}</a> in <a href="/post?category={{ $post->category->slug }}"class="text-decoration-none">{{ $post->category->name }}</a></p>
            <img src="https://source.unsplash.com/1600x900/?{{ $post->category->name }}" alt="{{ $post->category->name }}" class="img-fluid">
            <article class="my-3">
                <p>{!! $post->body !!}</p>

            </article>
                
            <a href="/post" class="d-block mt-5">Back to Post</a>
                
        </div>
    </div>
</div>
    
@endsection
