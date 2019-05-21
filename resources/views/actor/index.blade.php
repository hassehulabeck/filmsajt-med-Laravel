@extends('base')

@section('main')
    @foreach($actors as $actor) 
        <div class="actor">
            {{ $actor->name }}
            <h4>Har medverkat i följande filmer:</h4>
            @foreach ($actor->movies as $movie)
                <a href="/movies/{{$movie->id}}"> {{ $movie->title }}</a><br />
            @endforeach
        </div>
    @endforeach
    {{ $actors->links() }}
@endsection