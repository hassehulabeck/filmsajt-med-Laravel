@extends('base')

@section('main')
    @foreach($actors as $actor) 
        <div class="actor">
            <h1>{{ $actor->name }}</h1>
            <h4>Har medverkat i följande filmer:</h4>
            @foreach ($actor->movies as $movie)
                <a href="/movies/{{$movie->id}}"> {{ $movie->title }}, ({{ $movie->year }})</a><br />
            @endforeach
        </div>
    @endforeach
    {{ $actors->links() }}
@endsection