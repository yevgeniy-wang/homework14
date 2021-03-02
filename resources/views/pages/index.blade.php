@extends('layout')

@section('title', 'Homepage')

@section('content')
    <h2 class="m-3">Homepage</h2>

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('posts') }}">Posts</a>
    </div>
    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('users') }}">Users</a>
    </div>
    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('categories') }}">Categories</a>
    </div>
    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('tags') }}">Tags</a>
    </div>
@endsection
