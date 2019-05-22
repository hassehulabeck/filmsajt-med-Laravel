@extends('base')

@section('main')
    <div>
        <h1>{{ $actor->name}}</h1>
        <p>Kommer från {{ $actor->country }} </p>    
        <h3>Har varit med i följande filmer</h3>
        @foreach ($actor->movies as $movie)
            <a href="/movies/{{$movie->id}} "> {{ $movie->title }}, ({{ $movie->year }}) </a><br/>
        @endforeach
    </div> 
@endsection