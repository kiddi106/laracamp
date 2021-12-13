@extends('layouts.main')
@section('container')
    
<h1>Hallo ini adalah halaman About</h1>
<h2>{{ $nama }}</h2>
<h2>{{ $email }}</h2>
<img src="img/{{ $gambar }}" alt="" width="200" class="img-thumbnail rounded-circle">
@endsection
