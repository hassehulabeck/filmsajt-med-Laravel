@extends('base')

@section('main')
    @foreach($movies as $movie) 
        <div class="movie">
            <h1>{{ $movie->title }}</h1>
            <h4>Skådespelare</h4>
            @foreach ($movie->actors as $actor)
            <a href="/actors/{{$actor->id}}"> {{ $actor->name }} </a><br />
            @endforeach
        </div>
    @endforeach
    {{ $movies->links() }}
@endsection