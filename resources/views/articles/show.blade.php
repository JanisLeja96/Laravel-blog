@extends('layouts.app')
@section('content')
    <div class="container">
        <a href="{{ route('articles.index') }}" class="btn btn-primary">Back</a>
        <h2>{{ $article->title }}</h2>
        <p>{{ $article->content }}</p>
    </div>
@endsection
