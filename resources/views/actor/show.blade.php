@extends('base')

@section('main')
    <div>
        <h1>{{ $actor[0]->name}}</h1>
        <p>Kommer från {{ $actor[0]->country }} </p>    
        <h3>Har varit med i följande filmer</h3>
        @foreach ($actor[0]->movies as $movie)
            <a href="/movies/{{$movie->id}} "> {{ $movie->title }}, ({{ $movie->year }}) </a><br/>
        @endforeach
    </div> 
@endsection