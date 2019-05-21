@extends('base')

@section('main')
    <div>
        <h1>{{ $movie[0]->title}}</h1>
        <p>Premiär {{ $movie[0]->year }} </p>
        <h3>Skådespelare</h3>
        @foreach ($movie[0]->actors as $actor)
            <a href="/actors/{{$actor->id}} "> {{ $actor->name }} </a><br/>
        @endforeach
    </div> 
@endsection